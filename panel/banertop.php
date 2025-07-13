<?php
if ($showPanel)
{
	if (get_priv_controler($_GET['c']))
	{
		// zamiana na encje dla wybranych zmiennych
		$specialCharsNames = array ('name');
		foreach ($_POST as $k => $v)
		{
			if (in_array($k, $specialCharsNames))
			{
				$_POST[$k] = htmlspecialchars(strip_tags($v), ENT_QUOTES);		
			}
		}
				
		$TEMPL_PATH = CMS_TEMPL . DS . 'banertop.php';
	
		$showList = true;
		$showAddForm = false;
		$showEditForm = false;
		$showAnimType = false;
		
		// lista akcji dla ktorych zaladowac dodatkowe css
		$actionCSSList = array ('edit', 'add', 'imageAdded');
		if (in_array($_GET['action'],$actionCSSList) )	
		{
			setCSS('jquery.ui.theme.css', $css);
			setCSS('jquery.ui.tabs.css', $css);
			setCSS('jquery.ui.datepicker.css', $css);
		}	
					
		$res = new resClass;
		
		$pageTitle = 'Zdjęcia w banerze górnym';
		
		$crumbpath[] = array ('name' => $pageTitle, 'url' => $PHP_SELF . '?c=banertop');
		
		$currentPath = '..' . DS . 'files' . DS . $lang;
			
		/**
		 * Dodanie 
		 */
		
		if ($_GET['action'] == 'add')
		{
			$crumbpath[] = array ('name' => 'Dodaj zdjęcie do banera', 'url' => $PHP_SELF . '?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp'] . '&amp;action=' . $_GET['action']);
			
			$pageTitle = 'Dodaj zdjęcie do banera';
			$showAddForm = true;
			$showList = false;
			$showUpload = true;
			$showImage = false;
			$showEditImage = false;
			
			
			/**
			* Usunięcie załadowanego przy dodawaniu
			*/
			if ($_GET['act'] == 'deleteImg'){
			    $showUpload = true;
			    $showImage = false;
			    $file = str_replace('|', '.', $_GET['filename']);
			    $extension = getExt($file);
			    if (!in_array($extension, $cmsConfig['photos'])){
				$messageTab .= show_msg ('err', $ERR_file_not_allowed);
			    } 
			    else 
			    {
				@unlink('../files/pl/' . $file);
				@unlink('../files/pl/mini/' . $file);
				$messageTab .= show_msg ('msg', $MSG_file_del);
			    }
			}
			
			/**
			* Załadowanie zdjęcia
			*/
			if ($_GET['act'] == 'addFile'){
				
			    $showUpload = false;
			    $showImage = true;

			    $file = $_SESSION['tmpFilesSession'];
			    unset($_SESSION['tmpFilesSession']);
			    $file = stripslashes($file);
			    $file = utf8_encode($file);
			    $file = json_decode($file);
			    
			    $file = $file[0];
			    
			    $file = rawurldecode($file);
			    $file = str_replace('+', ' ', $file);
			    
			    $extension = getExt($file);
			    
			    
			    if (!in_array($extension, $cmsConfig['photos'])){
					$messageTab .= show_msg ('err', $ERR_file_not_allowed);
					$showUpload = true;
					$showImage = false;
			    } 
				else
			    {
					$TEMPL_PATH = CMS_TEMPL . DS . 'banertop.php';
					$showInner = true;
					include_once($TEMPL_PATH);
			    }
			}

			/**
			* Ręczna edycja załadowanego zdjęcia
			*/
			if ($_GET['act'] == 'editImg'){
				$showUpload = false;
				$showImage = false;
				$showEditImage = true;
				$file = str_replace('|', '.', $_GET['filename']);
				$extension = getExt($file);
				if (!in_array($extension, $cmsConfig['photos'])){
					$messageTab .= show_msg ('err', $ERR_file_not_allowed);
				}
			}
			/**
			* Zdjęcie poddane edycji
			*/
			if ($_GET['act'] == 'editImage'){
				$newFile = 'temp_'.str_replace('|', '.', $_GET['filename']);
				$newFileRename = substr($newFile, 5);
				$oldFile = str_replace('|', '.', $_GET['filename']);
				@unlink($currentPath . DS . 'mini' . DS . $oldFile);
				rename($currentPath . DS . 'mini' . DS . $newFile, $currentPath . DS . 'mini' . DS . $newFileRename);
				$file = $oldFile;
				$showUpload = false;
				$showImage = true;
			}
			
			if ($_GET['act'] == 'addPoz')
			{
				if ($_POST['new_window'] == '') 
					$_POST['new_window'] = 0;
				else
					$_POST['new_window'] = 1;
							
				if (!$_POST['name'])
				{
					$message .= show_msg ('err', $ERR_title);
					if ($_POST['filename'] != ''){
						$file = str_replace('|', '.', $_POST['filename']);
						$showUpload = false;
						$showImage = true;
						$extension = getExt($file);
					}
					
				}
				else
				{
					$addDate = date("Y-m-d H:i:s");	
					
					$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables['banertop'] . "` WHERE (id_page = ?) ";
					$params = array ( 'id_page' => $_GET['idp'] );				
					$res->bind_execute( $params, $sql);
					$maxPos = $res->data[0]['maxPos'] + 1;
					
					if ($_POST['pos']<=0 || trim($_POST['pos'])=='' || !is_numeric($_POST['pos'])) {
						$_POST['pos'] = 1;
					}
									
					if ($_POST['pos']>$maxPos){
						$_POST['pos'] = $maxPos;
					}
					
					$photo = str_replace('|', '.', $_POST['filename']);
					
					// posortowanie
					$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos+1 WHERE (pos>=?) AND (id_page=?)";
					$params = array (
							'pos' => $_POST['pos'],
							'id_page' => $_GET['idp'], 
							);							
					$res->bind_execute( $params, $sql);

					
					$sql = "INSERT INTO `" . $dbTables['banertop'] . "` VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, '0', ?, ?)";
					$params = array (
								'pos' => $_POST['pos'],
								'id_page' => $_GET['idp'],
								'name' => strip_tags($_POST['name']), 
								'photo' => $photo, 
								'lead_text' => $_POST['lead_text'], 
								'text' => $_POST['text'],
								'ext_url' => $_POST['ext_url'],
								'new_window' => $_POST['new_window'],
								'data' => $addDate,
								'lang' => $lang
								);							
					
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;		
					$idBan = $res->lastID; 
					
					if ( $numRows > 0)	
					{		
						$message .= show_msg ('msg', $MSG_add);
						monitor( $_SESSION['userData']['UID'], $MON_banertop_add . $_POST['name'] , get_ip() );
						unset($_SESSION['tmpFilesSession']);
												
						if ($_POST['save']) {
							$showAddForm = false;
							$showList = true;
						}				
					}							
				}
			}
	
			if ($_POST['saveAdd'] || !$_POST['save']) {
				$showAddForm = true;
			}
			
		}
		

			
		/**
		 * Edycja
		 */
		if ($_GET['action'] == 'edit')
		{
			$crumbpath[] = array ('name' => 'Edytuj baner', 'url' => $PHP_SELF . '?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp'] . '&amp;action=' . $_GET['action'].'&amp;id=' . $_GET['id']);
			$pageTitle = 'Edytuj baner';
			$showEditForm = true;	
			$showList = false;
			$showUpload = true;
			$showImage = false;
			$showEditImage = false;
			
			/**
			* Ręczna edycja załadowanego zdjęcia
			*/
			if ($_GET['act'] == 'editImg'){
				$showUpload = false;
				$showImage = false;
				$showEditImage = true;
				$file = str_replace('|', '.', $_GET['filename']);
				$extension = getExt($file);
				if (!in_array($extension, $cmsConfig['photos'])){
					$messageTab .= show_msg ('err', $ERR_file_not_allowed);
				}
			}

			/**
			* Zdjęcie poddane edycji
			*/
			if ($_GET['act'] == 'editImage'){
				$newFile = 'temp_'.str_replace('|', '.', $_GET['filename']);
				$newFileRename = substr($newFile, 5);
				$oldFile = str_replace('|', '.', $_GET['filename']);
				@unlink($currentPath . DS . 'mini' . DS . $oldFile);
				@unlink($currentPath . DS . $newFile);
				rename($currentPath . DS . 'mini' . DS . $newFile, $currentPath . DS . 'mini' . DS . $newFileRename);
				$file = $oldFile;
				$showUpload = false;
				$showImage = true;
			}			
			
			/**
			* Załadowanie zdjęcia
			*/
			if ($_GET['act'] == 'addFile'){

			    $showUpload = false;
			    $showImage = true;
			    
			    $file = $_SESSION['tmpFilesSession'];
			    unset($_SESSION['tmpFilesSession']);
			    $file = stripslashes($file);
			    $file = utf8_encode($file);
			    $file = json_decode($file);
			    
			    $file = $file[0];
			    $file = rawurldecode($file);
			    $file = str_replace('+', ' ', $file);
			    
			    $extension = getExt($file);
			    if (!in_array($extension, $cmsConfig['photos'])){
				    $messageTab .= show_msg ('err', $ERR_file_not_allowed);
				    $showUpload = true;
				    $showImage = false;
			    } else
			    {
				$TEMPL_PATH = CMS_TEMPL . DS . 'banertop.php';
				$showInner = true;
				include_once($TEMPL_PATH);			
			    }
			}			
			
			/**
			* Usunięcie zdjęcia
			*/
			if ($_GET['act'] == 'deleteImg'){
				$showUpload = true;
				$showImage = false;
				$file = str_replace('|', '.', $_GET['filename']);
				$extension = getExt($file);
				if (!in_array($extension, $cmsConfig['photos'])){
					$messageTab .= show_msg ('err', $ERR_file_not_allowed);
				} 
				else 
				{
					@unlink('../files/pl/' . $file);
					@unlink('../files/pl/mini/' . $file);
					$messageTab .= show_msg ('msg', $MSG_file_del);
					
					$sql = "UPDATE `" . $dbTables['banertop'] . "` SET `photo` = ? WHERE (`id_ban` = ?) LIMIT 1";
					$params = array (
							'photo' => '',
							'id_ban' => $_GET['id']
							);
					$res -> bind_execute($params, $sql);					
					/*
					if (file_exists('../files/pl/mini/' . $file))
					{
						@unlink('../files/pl/' . $file);
						@unlink('../files/pl/mini/' . $file);
						$messageTab .= show_msg ('msg', $MSG_file_del);
						
						$sql = "UPDATE `" . $dbTables['banertop'] . "` SET `photo` = ? WHERE (`id_ban` = ?) LIMIT 1";
						$params = array (
								'photo' => '',
								'id_ban' => $_GET['id']
								);
						$res -> bind_execute($params, $sql);
					} else {
						$messageTab .= show_msg ('err', $ERR_file_not_extists);
					}
					*/
				}
			}
			
			
			if ($_GET['act'] == 'editPoz')
			{
				if ($_POST['new_window'] == '') 
					$_POST['new_window'] = 0;
				else
					$_POST['new_window'] = 1;
				
				if (!$_POST['name'])
				{
					$message .= show_msg ('err', $ERR_title);
				}
				else
				{
					$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables['banertop'] . "` WHERE (id_page = ?) ";
					$params = array ( 'id_page' => $_GET['idp'] );				
					$res->bind_execute( $params, $sql);
					$maxPos = $res->data[0]['maxPos'];
					
					if ($_POST['pos']<=0 || trim($_POST['pos'])=='' || !is_numeric($_POST['pos'])) {
						$_POST['pos'] = 1;
					}
									
					if ($_POST['pos']>$maxPos){
						$_POST['pos'] = $maxPos;
					}	
					
					if($_POST['old_pos'] >= $_POST['pos']) 
					{
						// posortowanie
						$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos+1 WHERE (pos>=?) AND (pos<?) AND (id_page=?)";
						$params = array (
								'pos' => $_POST['pos'],
								'old_pos' => $_POST['old_pos'],
								'id_page' => $_GET['idp'], 
								);							
						$res->bind_execute( $params, $sql);						
					} 
					else 
					{
						// posortowanie
						$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos-1 WHERE (pos>?) AND (pos<=?) AND (id_page=?)";
						$params = array (
								'old_pos' => $_POST['old_pos'],
								'pos' => $_POST['pos'],
								'id_page' => $_GET['idp'], 
								);							
						$res->bind_execute( $params, $sql);						
					}
					
					$photo = str_replace('|', '.', $_POST['filename']);
					
					$sql = "UPDATE `" . $dbTables['banertop'] . "` SET 
							pos=?, name = ?, photo = ?, lead_text = ?, text = ?, ext_url = ?, new_window = ?  WHERE (`id_ban` = ?) LIMIT 1";
	
					$params = array (
								'pos' => $_POST['pos'],
								'name' => strip_tags($_POST['name']), 
								'photo' => $photo, 
								'lead_text' => $_POST['lead_text'], 
								'text' => $_POST['text'],
								'ext_url' => $_POST['ext_url'],
								'new_window' => $_POST['new_window'],
								'id_ban' => $_GET['id'] 
								);							
					
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;
	
					if ( $numRows > 0)	
					{		
						$message .= show_msg ('msg', $MSG_edit);
						monitor( $_SESSION['userData']['UID'], $MON_banertop_edit . $_POST['name'] , get_ip() );
						
						if ($_POST['saveEdit']) {
							$showEditForm = false;
							$showList = true;
						}						
					}				
				}
			}
			
			$sql = "SELECT * FROM `" . $dbTables['banertop'] . "` WHERE (`id_ban`= ?) LIMIT 1";
			$params = array ('id_ban' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
			if ($row['photo'] == ''){
				$showUpload = true;
				$showImage = false;
			} else {
				$showUpload = false;
				$showImage = true;
				$extension = getExt($row['photo']);
				$file = $row['photo'];
			}
			
			if ($_GET['act'] == 'imageAdded' || $_GET['act'] == 'editImage'){
				$showUpload = false;
				$showImage = true;
				$extension = getExt(str_replace('|', '.', $_GET['filename']));
				$file = str_replace('|', '.', $_GET['filename']);
			}
			
			if ($_POST['saveList'])
			{
			    $showEditForm = false;
			    $showList = true;
			}
			
		}
		
		/**
		 * Usuwanie
		 */
		if ($_GET['action'] == 'delete')
		{
			$sql = "SELECT * FROM `" . $dbTables['banertop'] . "` WHERE (id_ban = ?) LIMIT 1";	
			$params = array ('id_ban' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];	
			$banName = $row['name'];
			$banPos = $row['pos'];
			$banPhoto = $row['photo'];
						
			$sql = "DELETE FROM `" . $dbTables['banertop'] . "` WHERE (`id_ban` = ?) LIMIT 1";
			$params = array ('id_ban' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$numRows = $res->numRows;	
	
			if ( $numRows > 0)		
			{		
				$message .= show_msg ('msg', $MSG_del);
				monitor( $_SESSION['userData']['UID'], $MON_banertop_del .  $banName, get_ip() );	
							
				// zmiana pozycji
				$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (id_page = ?)";
				$params = array (
							'pos' => $banPos, 
							'id_page' => $_GET['idp'], 
							);
				$res->bind_execute( $params, $sql);
				
				@unlink('../files/pl/' . $banPhoto);
				@unlink('../files/pl/mini/' . $banPhoto);	
			}	
		}
		
			
		/**
		 * Przesuniecie pozycji do gory
		 */			
		if ($_GET['action'] == 'posTop')
		{
			$sql = "SELECT * FROM `" . $dbTables['banertop'] . "` 	WHERE (`" . $dbTables['banertop'] . "`.id_ban = ?) LIMIT 1";
			$params = array ('id_ban' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
			$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos+1 WHERE (pos = ?)";
			$params = array (
							'pos' => $row['pos']-1, 
							);
			$res->bind_execute( $params, $sql);
			
			$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos-1 WHERE (id_ban= ?)";
			$params = array (
							'id_ban' => $_GET['id']
							);
			$res->bind_execute( $params, $sql);
		}	
	
	
		/**
		 * Przesuniecie pozycji na dol
		 */		
		if ($_GET['action'] == 'posBot')
		{
			$sql = "SELECT * FROM `" . $dbTables['banertop'] . "` 	WHERE (`" . $dbTables['banertop'] . "`.id_ban = ?) LIMIT 1";
			$params = array ('id_ban' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];
			
			$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos-1 WHERE (pos = ?)";
			$params = array (
							'pos' => $row['pos']+1, 
							);
			$res->bind_execute( $params, $sql);
			
			$sql = "UPDATE `" . $dbTables['banertop'] . "` SET pos=pos+1 WHERE (id_ban= ?)";
			$params = array (
							'id_ban' => $_GET['id']
							);
			$res->bind_execute( $params, $sql);
		}
											
		/**
		 * De-Aktywacja
		 */
		if ($_GET['action'] == 'noactive')
		{
			$sql = "UPDATE `" . $dbTables['banertop'] . "` SET active = ? WHERE (`id_ban` = ?) LIMIT 1";
			$params = array (
							'active' => 0, 
							'id_ban' => $_GET['id']
							);
			$res->bind_execute( $params, $sql);
		}
		
		/**
		 * Aktywacja
		 */
		if ($_GET['action'] == 'active')
		{
			$sql = "UPDATE `" . $dbTables['banertop'] . "` SET active = ? WHERE (`id_ban` = ?) LIMIT 1";
			$params = array (
							'active' => 1, 
							'id_ban' => $_GET['id']
							);
			$res->bind_execute( $params, $sql);
		}
	
	 					
		/**
		 * Pobranie 
		 */
		if ($showList)
		{		 
			
			$sql = "SELECT COUNT(id_ban) AS total_records FROM `" . $dbTables['banertop'] . "` WHERE (`id_page`= ?)";
			$params = array( 'id_page' => $_GET['idp']);
			$res->bind_execute( $params, $sql);
			
			$r = $res->data[0];	
			$numRows = $r['total_records'];		
				
			if ($numRows > 0)
			{						
				$sql = "SELECT * FROM `" . $dbTables['banertop'] . "` WHERE (`id_page` = ?) ORDER BY pos ";	
						
				$params = array( 'id_page'=> $_GET['idp']);
				$res->bind_execute( $params, $sql);
				$outRow = $res->data;	

				$pagination = pagination ($numRows, 100, 2, $_GET['s']);		
			}	
			else
			{
				$message .= show_msg ('err', 'Brak przypisanych banerów.');	
			}						
		}
	}
	else
	{
		$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
		$message .= show_msg ('err', $ERR_priv_access);	
	}
}
?>