<?php
$_GET['action'] = $_GET['action'] ?? '';
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
	if (get_priv_controler('page') || get_priv_controler('dynamic_menu'))
	{	
		$TEMPL_PATH = CMS_TEMPL . DS . 'photos.php';
	
		$showList = true;
		$showAddForm = false;
		$showEditForm = false;
		$showEditImage = false;
		$copyFile = false;
		$copyFileSel = false;
				
		$currentPath = '..' . DS . 'files' . DS . $lang;
	
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
		* Obróbka obrazka
		*/
		if ($_GET['action'] == 'editImg'){
			$pageTitle = 'Obróbka obrazu';
			
			$showEditImage = true;
			$showList = false;
			
			switch ($_GET['c']) {
				case 'files':
					$idTable = $dbTables['files'];
					$id = "id_file";
				case 'photos':
				default:
					$idTable = $dbTables['photos'];
					$id = "id_photo";
					break;
			}
			
			$sql = "SELECT * FROM `" . $idTable . "` WHERE (`".$id."` = ?)";
			$params = array (
						$id => $_GET['idf']
						);
			$res -> bind_execute($params, $sql);
			$outRow = $res -> data;
			$filename = $outRow[0]['file'];
			$idFilename = $outRow[0][$id];
			//debug($res);
		}
		/**
		* Gotowy obrazek
		*/
		if ($_GET['action'] == 'editImage'){
			$showList = true;
			
			$newFile = 'temp_'.$_GET['filename'];
			$oldFile = $_GET['filename'];
			@unlink($currentPath . DS . $oldFile);
			@unlink($currentPath . DS . 'mini' . DS . $oldFile);
			rename($currentPath . DS . $newFile, $currentPath . DS . $oldFile);
			rename($currentPath . DS . 'mini' . DS . $newFile, $currentPath . DS . 'mini' . DS . $oldFile);
			$message .= show_msg ('msg', 'Plik ' . $oldFile . ' został poprawnie zapisany.');
			monitor( $_SESSION['userData']['UID'], 'Obróbka obrazu: '.$newFileRename , get_ip() );			
		}
			
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
				$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables['photos'] . "` WHERE (id_page = ?) AND (type = ?) ";
				$params = array ( 
							'id_page' => $_GET['id'], 
							'type' => $_SESSION['type_to_files']  
							);				
				$res->bind_execute( $params, $sql);
				$maxPos = $res->data[0]['maxPos'];
				
				if ($_POST['photo_pos']<=0 || trim($_POST['photo_pos'])=='' || !is_numeric($_POST['photo_pos'])) {
					$_POST['photo_pos'] = 1;
				}
								
				if ($_POST['photo_pos']>$maxPos){
					$_POST['photo_pos'] = $maxPos;
				}	
				
				if($_POST['photo_old_pos'] >= $_POST['photo_pos']) 
				{
					// posortowanie
					$sql = "UPDATE `" . $dbTables['photos'] . "` SET pos=pos+1 WHERE (pos>=?) AND (pos<?) AND (id_page=?) AND (type = ?)";
					$params = array (
							'pos' => $_POST['photo_pos'],
							'old_pos' => $_POST['photo_old_pos'],
							'id_page' => $_GET['id'],
							'type' => $_SESSION['type_to_files']   
							);							
					$res->bind_execute( $params, $sql);	
				} 
				else 
				{
					// posortowanie
					$sql = "UPDATE `" . $dbTables['photos'] . "` SET pos=pos-1 WHERE (pos>?) AND (pos<=?) AND (id_page=?) AND (type= ?)";
					$params = array (
							'old_pos' => $_POST['photo_old_pos'],
							'pos' => $_POST['photo_pos'],
							'id_page' => $_GET['id'], 
							'type' => $_SESSION['type_to_files']  
							);							
					$res->bind_execute( $params, $sql);		
				}
				
				$sql = "UPDATE `" . $dbTables['photos'] . "` SET 
						pos=?, name = ?, keywords = ?  WHERE (`id_photo` = ?) LIMIT 1";

				$params = array (
							'pos' => $_POST['photo_pos'],
							'name' => strip_tags($_POST['photo_name']), 
							'keywords' => strip_tags($_POST['photo_keywords']), 
							'id_photo' => $_GET['idf'] 
							);							
	
				$res->bind_execute( $params, $sql);
				$numRows = $res->numRows;		

				if ( $numRows > 0)	
				{		
					$message .= show_msg ('msg', $MSG_edit);
					monitor( $_SESSION['userData']['UID'], $MON_file_edit . $_POST['photo_name'] , get_ip() );
					
					$showEditForm = false;
					$showList = true;
				}	
			}
			
	
			$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_photo`= ?) LIMIT 1";
			$params = array ('id_photo' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];	
		}
		
		/**
		 * Usuwanie
		 */
		if ($_GET['action'] == 'delete')
		{
			$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (id_photo = ?) LIMIT 1";	
			$params = array ('id_photo' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];	
			$photoName = $row['file'];
			$photoPos = $row['pos'];
						
			$sql = "DELETE FROM `" . $dbTables['photos'] . "` WHERE (`id_photo` = ?) LIMIT 1";
			$params = array ('id_photo' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$numRows = $res->numRows;	
	
			if ( $numRows > 0)		
			{		
				$message .= show_msg ('msg', $MSG_del);
				monitor( $_SESSION['userData']['UID'], $MON_file_del .  $photoName, get_ip() );	
							
				// zmiana pozycji
				$sql = "UPDATE `" . $dbTables['photos'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (id_page = ?) AND (type = ?)";
				$params = array (
							'pos' => $photoPos, 
							'id_page' => $_GET['id'], 
							'type' => $_SESSION['type_to_files']  
							);
				$res->bind_execute( $params, $sql);
				
				del_file($photoName, 'photos');	
			}				
		}
			
		/**
		 * Przesuniecie pozycji do gory
		 */			
		if ($_GET['action'] == 'posTop')
		{
			$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_photo`= ?) LIMIT 1";
			$params = array ('id_photo' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
			$sql = "UPDATE `" . $dbTables['photos'] . "` SET pos=pos+1 WHERE (pos = ?) AND (id_page= ?) AND (type = ?)";
			$params = array (
							'pos' => $row['pos']-1, 
							'id_page' => $_GET['id'],
							'type' => $_SESSION['type_to_files']  
							);
			$res->bind_execute( $params, $sql);
			
			$sql = "UPDATE `" . $dbTables['photos'] . "` SET pos=pos-1 WHERE (id_photo= ?)";
			$params = array (
							'id_photo' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}	
	
		/**
		 * Przesuniecie pozycji na dol
		 */		
		if ($_GET['action'] == 'posBot')
		{
			$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_photo`= ?) LIMIT 1";
			$params = array ('id_photo' => $_GET['idf']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
			$sql = "UPDATE `" . $dbTables['photos'] . "` SET pos=pos-1 WHERE (pos = ?) AND (id_page= ?) AND (type = ?)";
			$params = array (
							'pos' => $row['pos']+1, 
							'id_page' => $_GET['id'],
							'type' => $_SESSION['type_to_files']  
							);
			$res->bind_execute( $params, $sql);
			
			$sql = "UPDATE `" . $dbTables['photos'] . "` SET pos=pos+1 WHERE (id_photo= ?)";
			$params = array (
							'id_photo' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}	
									
		/**
		 * De-Aktywacja
		 */
		if ($_GET['action'] == 'noactive')
		{
			$sql = "UPDATE `" . $dbTables['photos'] . "` SET active = ? WHERE (`id_photo` = ?) LIMIT 1";
			$params = array (
							'active' => 0, 
							'id_photo' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}
		
		/**
		 * Aktywacja
		 */
		if ($_GET['action'] == 'active')
		{
			$sql = "UPDATE `" . $dbTables['photos'] . "` SET active = ? WHERE (`id_photo` = ?) LIMIT 1";
			$params = array (
							'active' => 1, 
							'id_photo' => $_GET['idf']
							);
			$res->bind_execute( $params, $sql);
		}
		
		/**
		*	Dodanie zdjęć	
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
		    	$message .= show_msg ('err', $ERR_no_photo_alt);
		    }
		}
	
								
		/**
		 * Pobranie files
		 */
		if ($showList)
		{		 
			
			$sql = "SELECT COUNT(id_photo) AS total_records FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?)";
			$params = array( 
						'id_page' => $_GET['id'],
						'type' => $_SESSION['type_to_files']  
						);
			$res->bind_execute( $params, $sql);
			
			$r = $res->data[0];	
			$numRows = $r['total_records'];		
				
			if ($numRows > 0)
			{						
				$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?) ORDER BY pos"; //  LIMIT ".$sql_start.", ".$sql_limit;	
						
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
				$message .= show_msg ('info', 'Brak przypisanych zdjęć.');	
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