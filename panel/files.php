<?php
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('CMS_BASE')) {
	define( 'CMS_BASE', dirname(__FILE__) );
}
if (!defined('CMS_ROOT')) {
	$parts = explode( DS, CMS_BASE );
	array_pop( $parts );
	define( 'CMS_ROOT', implode( DS, $parts ) ); 
}
include_once ( CMS_ROOT . DS . 'includes' . DS . 'check.php' );
if ($showPanel)
{
	$_GET['action'] = $_GET['action'] ?? '';
	$_GET['act'] = $_GET['act'] ?? '';
	if (get_priv_controler('page') || get_priv_controler('dynamic_menu'))
	{	
		$TEMPL_PATH = CMS_TEMPL . DS . 'files.php';
	
		$showList = true;
		$showAddForm = false;
		$showEditForm = false;	

		$res = new resClass;
		
		// menu type
		$sql = "SELECT * FROM `" . $dbTables['menu_types'] . "` WHERE `menutype` = ? AND `lang` = ? LIMIT 1";
		$params = array (
					'menutype' => $_SESSION['mt'], 
					'lang' => $lang
					);
		$res->bind_execute( $params, $sql);
		$menuType = $res->data[0]['name'];	
		
		// strona / grupa	
		$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE `id` = ? AND `lang` = ? LIMIT 1";
		$params = array (
					'id' => $_GET['id'], 
					'lang' => $lang
					);
		$res->bind_execute( $params, $sql);
		$pageName = $res->data[0]['name'];
			
	
		/**
		 * Upload
		 */
		if ($_GET['action'] == 'copy')
		{
			$pageTitle = 'Prześlij pliki';
			$showEditForm = false;	
			$showList = false;	
			$copyFile = true;
			
			if ($_GET['act'] == 'selUploadFiles') {
				$copyFileSel = true;
			}	
					
		}
			
		/**
		 * Edycja
		 */
		if ($_GET['action'] == 'edit')
		{
			$pageTitle = 'Edytuj plik';
			$showEditForm = true;	
			$showList = false;	
			
			if ($_GET['act'] == 'editPoz')
			{
				$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables['files'] . "` WHERE (id_page = ?) AND (type = ?) ";
				$params = array ( 
							'id_page' => $_GET['id'], 
							'type' => $_SESSION['type_to_files']  
							);				
				$res->bind_execute( $params, $sql);
				$maxPos = $res->data[0]['maxPos'];
				
				if ($_POST['file_pos']<=0 || trim($_POST['file_pos'])=='' || !is_numeric($_POST['file_pos'])) {
					$_POST['file_pos'] = 1;
				}
								
				if ($_POST['file_pos']>$maxPos){
					$_POST['file_pos'] = $maxPos;
				}	
				
				if($_POST['file_old_pos'] >= $_POST['file_pos']) 
				{
					// posortowanie
					$sql = "UPDATE `" . $dbTables['files'] . "` SET pos=pos+1 WHERE (pos>=?) AND (pos<?) AND (id_page=?) AND (type = ?)";
					$params = array (
							'pos' => $_POST['file_pos'],
							'old_pos' => $_POST['file_old_pos'],
							'id_page' => $_GET['id'],
							'type' => $_SESSION['type_to_files']   
							);							
					$res->bind_execute( $params, $sql);	
				} 
				else 
				{
					// posortowanie
					$sql = "UPDATE `" . $dbTables['files'] . "` SET pos=pos-1 WHERE (pos>?) AND (pos<=?) AND (id_page=?) AND (type= ?)";
					$params = array (
							'old_pos' => $_POST['file_old_pos'],
							'pos' => $_POST['file_pos'],
							'id_page' => $_GET['id'], 
							'type' => $_SESSION['type_to_files']  
							);							
					$res->bind_execute( $params, $sql);		
				}
				
				$sql = "UPDATE `" . $dbTables['files'] . "` SET 
						pos=?, name = ?, keywords = ?  WHERE (`id_file` = ?) LIMIT 1";

				$params = array (
							'pos' => $_POST['file_pos'],
							'name' => strip_tags($_POST['file_name']), 
							'keywords' => strip_tags($_POST['file_keywords']), 
							'id_file' => $_GET['idf'] 
							);							
	
				$res->bind_execute( $params, $sql);
				$numRows = $res->numRows;		

				if ( $numRows > 0)	
				{		
					$message .= show_msg ('msg', $MSG_edit);
					monitor( $_SESSION['userData']['UID'], $MON_file_edit . $_POST['file_name'] , get_ip() );
					
					$showEditForm = false;
					$showList = true;
				}	
			}

			$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_file`= ?) LIMIT 1";
			$params = array ('id_file' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
		}
		
		/**
		 * Usuwanie
		 */
		if ($_GET['action'] == 'delete')
		{
			$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (id_file = ?) LIMIT 1";	
			$params = array ('id_file' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];	
			$fileName = $row['file'];
			$filePos = $row['pos'];
						
			$sql = "DELETE FROM `" . $dbTables['files'] . "` WHERE (`id_file` = ?) LIMIT 1";
			$params = array ('id_file' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$numRows = $res->numRows;	
	
			if ( $numRows > 0)		
			{		
				$message .= show_msg ('msg', $MSG_del);
				monitor( $_SESSION['userData']['UID'], $MON_file_del .  $fileName, get_ip() );	
							
				// zmiana pozycji
				$sql = "UPDATE `" . $dbTables['files'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (id_page = ?) AND (type = ?)";
				$params = array (
							'pos' => $filePos, 
							'id_page' => $_GET['id'], 
							'type' => $_SESSION['type_to_files']  
							);
				$res->bind_execute( $params, $sql);
				
				del_file($fileName, 'download');	
			}				
		}
			
		/**
		 * Przesuniecie pozycji do gory
		 */			
		if ($_GET['action'] == 'posTop')
		{
			$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_file`= ?) LIMIT 1";
			$params = array ('id_file' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
			$sql = "UPDATE `" . $dbTables['files'] . "` SET pos=pos+1 WHERE (pos = ?) AND (id_page= ?) AND (type = ?)";
			$params = array (
							'pos' => $row['pos']-1, 
							'id_page' => $_GET['id'],
							'type' => $_SESSION['type_to_files']  
							);
			$res->bind_execute( $params, $sql);
			
			$sql = "UPDATE `" . $dbTables['files'] . "` SET pos=pos-1 WHERE (id_file= ?)";
			$params = array (
							'id_file' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}	
	
		/**
		 * Przesuniecie pozycji na dol
		 */		
		if ($_GET['action'] == 'posBot')
		{
			$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_file`= ?) LIMIT 1";
			$params = array ('id_file' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
			$sql = "UPDATE `" . $dbTables['files'] . "` SET pos=pos-1 WHERE (pos = ?) AND (id_page= ?) AND (type = ?)";
			$params = array (
							'pos' => $row['pos']+1, 
							'id_page' => $_GET['id'],
							'type' => $_SESSION['type_to_files']  
							);
			$res->bind_execute( $params, $sql);
			
			$sql = "UPDATE `" . $dbTables['files'] . "` SET pos=pos+1 WHERE (id_file= ?)";
			$params = array (
							'id_file' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}	
									
		/**
		 * De-Aktywacja
		 */
		if ($_GET['action'] == 'noactive')
		{
			$sql = "UPDATE `" . $dbTables['files'] . "` SET active = ? WHERE (`id_file` = ?) LIMIT 1";
			$params = array (
							'active' => 0, 
							'id_file' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}
		
		/**
		 * Aktywacja
		 */
		if ($_GET['action'] == 'active')
		{
			$sql = "UPDATE `" . $dbTables['files'] . "` SET active = ? WHERE (`id_file` = ?) LIMIT 1";
			$params = array (
							'active' => 1, 
							'id_file' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}

		/**
		* Dodanie plików	
		*/

		if ($_GET['action'] == 'addFiles'){
		    
		    if (isset($_SESSION['tmpFilesSession']))
		    {
			$files = $_SESSION['tmpFilesSession'];
			unset($_SESSION['tmpFilesSession']);

			$files = stripslashes($files);
			$files = utf8_encode($files);
			$files = json_decode($files);
			
			foreach ($files as $value)
			{
			    $value = rawurldecode($value);
			    $value = str_replace('+', ' ', $value);			    
			    $message .= show_msg('msg', 'Plik: ' . $value . ' został dodany.');
			    
			    monitor( $_SESSION['userData']['UID'], 'Dodanie pliku ' . $value . ' na stronie' . ': ' . $pageName , get_ip() );
			}
		    
		    }
		}
		
								
		/**
		 * Pobranie files
		 */
		if ($showList)
		{		 
			
			$sql = "SELECT COUNT(id_file) AS total_records FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?)";
			$params = array( 
						'id_page' => $_GET['id'],
						'type' => $_SESSION['type_to_files']  
						);
			$res->bind_execute( $params, $sql);
			
			$r = $res->data[0];	
			$numRows = $r['total_records'];		
				
			if ($numRows > 0)
			{						
				$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) ORDER BY pos"; //  LIMIT ".$sql_start.", ".$sql_limit;	
						
				$params = array( 
					'id_page'=> $_GET['id'],
					'type' => $_SESSION['type_to_files']  
					);
				$res->bind_execute( $params, $sql);
				$outRow = $res->data;	

				//$pagination = pagination ($numRows, $cmsConfig['limit'], 2, $_GET['s']);		
			}	
			else
			{
				$message .= show_msg ('info', 'Brak przypisanych plików.');	
			}						
		}
		
		include($TEMPL_PATH);
	}
	else
	{
		$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
		$message .= show_msg ('err', $ERR_priv_access);	
	}
}

?>