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
	$_GET['filename'] = $_GET['filename'] ?? '';
	$_GET['action'] = $_GET['action'] ?? '';
	$_GET['d'] = $_GET['d'] ?? '';
	if (get_priv_controler($_GET['c']))
	{	
		$_GET['act'] = $_GET['act'] ?? '';
		$_GET['filename'] = str_replace('|', '.', $_GET['filename']);
		
	
		$TEMPL_PATH = CMS_TEMPL . DS . 'explorer.php';
		$pageTitle = ($pageTitle ?? '').$TXT_menu_files;
		
		$crumbpath[] = array ('name' => $TXT_menu_files, 'url' => $PHP_SELF . '?c=' . $_GET['c']);	
		
		$currentPath = '..' . DS . FILES_DIR;
		$basepath = realpath($currentPath);
			
		$isPathChanged = false;
		if ($_GET['d'] != ''){		
			$newPath = realpath($currentPath .= DS . $_GET['d']);
			if ($newPath){ // ścieżka instnieje
				$isPathChanged = true;
				$currentPath = $newPath;
				$_SESSION['userData']['currentPath'] = $currentPath;
				$back = substr($_GET['d'], 0, strrpos($_GET['d'], '/'));
			}
		} 

		if (!$isPathChanged) {
			$_GET['d'] = '.';
			$back = $_GET['d'];
		}
		if ($back == ''){
			$back = '.';
		}
		
		$showAddForm = false;
		$showEditForm = false;
		$showEditFileForm = false;
		$showFileList = true;
		$showEditImage = false;
		$copyFile = false;
		$copyFileSel = false;
		
		//usunięcie katalogu
		if ($_GET['action'] == 'deleteDir'){

			// orchona przed path traversal
			$dirFullPath = realpath($currentPath . DS . $_GET['filename']);
			// orchona przed path traversal
			if ($dirFullPath)
			{
				// orchona przed path traversal
				if (str_starts_with($dirFullPath, $basepath) && $dirFullPath != $basepath) {
					// delete
					if (file_exists($dirFullPath)) {
						removeRecurenceDir ( $dirFullPath );
						$message .= show_msg ('msg', $MSG_dir_del);
						monitor( $_SESSION['userData']['UID'], $MON_dir_del . '<strong> ' . $_GET['filename'] . '</strong>', get_ip() );
					} else {
						$message .= show_msg ('err', $ERR_dir_noexists );
					}
				}
			}
			
			
			//
		}
		
		//usunięcie pliku
		if ($_GET['action'] == 'deleteFile') {
			
			$filename = str_replace('|', '.', $_GET['filename']);
			// orchona przed path traversal
			$fileFullPath = realpath($currentPath . DS . $filename);
			// orchona przed path traversal
			if ($fileFullPath)
			{
				// orchona przed path traversal
				if (str_starts_with($fileFullPath, $basepath) && $fileFullPath != $basepath){
					// delete
					if (file_exists($fileFullPath)){
						unlink($fileFullPath);
						$message .= show_msg ('msg', $MSG_file_del);
						monitor( $_SESSION['userData']['UID'], $MON_file_del . '<strong> ' . $filename . '</strong>', get_ip() );			
					} else {
						$message .= show_msg ('err', $ERR_file_noexists );
					}
				}
			}
		}
		
		//dodanie katalogu
		if ($_GET['action'] == 'add'){
			$showAddForm = true;
			
			if ($_GET['act'] == 'addDir'){
				$newDir = trans_url_name(trim($_POST['filename']));

				// orchona przed path traversal
				$dirFullPath = realpath($currentPath . DS . $newDir);
				// orchona przed path traversal
				if ($dirFullPath) {
					if (str_starts_with($dirFullPath, $basepath))
					{
					// orchona przed path traversal
						if (trim($newDir) == ''){
							$message .= show_msg ('err', $ERR_dir_name);
						} else {
							if (file_exists($dirFullPath)){
								$message .= show_msg ('err', $ERR_dir_exists . ':  ' . $_GET['d'] . '.');
							} else {
								if(mkdir($dirFullPath, 0777)){
									$message .= show_msg ('msg', $MSG_dir_add);
									monitor( $_SESSION['userData']['UID'], $MON_dir_add . '<strong>' . $newDir . '</strong>' , get_ip() );
									$showAddForm = false;
								} else {
									$message .= show_msg ('err', $ERR_contact);
								}
							}
						}
					}
				}
			}
		}
		
		// edycja obrazka 
		// ? editFile v2 - połączono z editFile
		/*
		if ($_GET['action'] == 'editImage'){
			
			$newFile = 'temp_'.str_replace('|', '.', $_GET['image']);
			$newFileRename = substr($newFile, 5);
			$oldFile = str_replace('|', '.', $_GET['image']);
			@unlink($currentPath . DS . $oldFile);
			rename($currentPath . DS . $newFile, $currentPath . DS . $newFileRename);
			$message .= show_msg ('msg', 'Plik ' . $newFileRename . ' został poprawnie zapisany.');
			monitor( $_SESSION['userData']['UID'], 'Obróbka obrazu: '.$newFileRename , get_ip() );
		}*/
		
		//dodanie plików ?
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
			    monitor( $_SESSION['userData']['UID'], 'Dodanie pliku: ' . $value . ' w katalogu: '. $_GET['d'] , get_ip() );
			}
		    }
		}
	
		//edycja plików
		if($_GET['action'] == 'editImg'){
			$showFileList = false;
			$showEditImage = true;
		}
		
		//edycja nazwy katalogu
		if($_GET['action'] == 'editDir'){
			$showEditForm = true;
			
			if ($_GET['filename'] != ''){
				$filename = $_GET['filename'];
			} else {
				$filename = $_POST['filename'];
			}
			
			if ($_GET['act'] == 'updateDir'){
				$oldDir = $_POST['oldName'];
				// ochrona przed path traversal
				$oldDirFullPath = $currentPath . DS . $oldDir;
				
				$newDir = trans_url_name(trim($_POST['filename']), $transPL);
				// ochrona przed path traversal
				$newDirFullPath = $currentPath . DS . $newDir; 
				
				if ($oldDirFullPath && $newDirFullPath)
				{
					if (str_starts_with($oldDirFullPath, $basePath) && str_starts_with($newDirFullPath, $basepath))
					{
						if (trim($newDir) == ''){
							$message .= show_msg ('err', $ERR_dir_name);
						} else {
							if (is_dir($currentPath . DS . $newDir)){
								$message .= show_msg ('err', $ERR_dir_exists . ':  ' . $_GET['d'] . '.');
							} else {
								if (rename( $oldDirFullPath, $newDirFullPath)){
									$message .= show_msg ('msg', $MON_dir_edit . ': <strong>' . $oldDir . '</strong> ' . $MON_to . ' <strong>' . $newDir . '</strong>');
									monitor( $_SESSION['userData']['UID'], $MON_dir_edit . ': <strong>' . $oldDir . '</strong> ' . $MON_to . ' <strong>' . $newDir . '</strong>' , get_ip() );
									$showEditForm = false;
								} else {
									$message .= show_msg ('err', $ERR_contact);
								}				
							}
						}
					}
				}
			}	
		}
		
		//kopiowanie plikow
		if ($_GET['action'] == 'copy')
		{
			$copyFile = true;
			if ($_GET['act'] == 'selUploadFiles') {
				$copyFileSel = true;
			}

			if ($_GET['act'] == 'uploadFiles') 
			{
				if ($uploadPath == '.' || $uploadPath == '')
				{
					$uploadPath = '..' . DS . FILES_DIR;
				} 
				else
				{
					$uploadPath = '..' . DS . FILES_DIR . DS . substr($uploadPath, 2);
				}
				
				for ($i=1; $i<=$_GET['filesNum']; $i++)
				{
					if (is_uploaded_file($_FILES['file'.$i]['tmp_name']))
					{
						$ext = getExt($_FILES['file'.$i]['name']);

						$stat = 0;
						if (in_array( $ext, $cmsConfig['upload_files'] ))
						{
							// ochrona przed path traversal
							$newNameFullPath = realpath( $currentPath . DS . trans_url_name_may($_FILES['file'.$i]['name']) );
							// ochrona przed path traversal
							if ($newNameFullPath) 
							{
								// ochrona przed path traversal
								if( str_starts_with($newNameFullPath, $basepath) )
								{
									// copy
									$stat = 1;
									if ( file_exists($newNameFullPath) )
									{
										$message .= show_msg ('err', 'Plik o nazwie ' . $_FILES['file'.$i]['name'] . ' już istnieje.');
									}
									else if (move_uploaded_file($_FILES['file'.$i]['tmp_name'], $newNameFullPath))
									{
										$message .= show_msg ('msg', 'Plik o nazwie ' . $_FILES['file'.$i]['name'] . ' został skopiowany.');
										monitor( $_SESSION['userData']['UID'], 'Pliki na serwerze - Plik ' .  $_FILES['file'.$i]['name'] . ' - został skopiowany.' , get_ip() );
									}
									else
									{
										$message .= show_msg ('err', 'Wystąpił błąd.');						
									}
								}
							}
						}
					
						if ($stat==0)
						{
							$message .= show_msg ('err', 'Plik ['.$_FILES['file'.$i]['name'].'] jest w niedozwolonym formacie.');	
						}									
					}
				}
			}			
		}
		
		// edycja nazwy
		if ($_GET['action'] == 'editFile' || $_GET['action'] == 'editImage')
		{
			$showEditFileForm = true;
			
			if ($_GET['filename'] != ''){
				
				$extension = getExt($_GET['filename']);
				
				$filename = substr($_GET['filename'], 0, strrpos($_GET['filename'], '.'));
			} else {
				
				$extension = $_POST['extension'];
				$filename = $_POST['filename'];
			
			}
			
			if ($_GET['act'] == 'updateFile'){
				
				$newName = $_POST['filename'] . '.' .$_POST['extension'] ;
				$newNameFullPath = realpath($currentPath . DS . $newName); // ochrona przed path traversal
				$oldName = $_POST['oldName'];
				$oldNameFullPath = realpath($currentPath . DS . $oldName); // ochrona przed path traversal

				// ochrona przed path traversal
				if ( $oldNameFullPath && $newNameFullPath )
				{
					// ochrona przed path traversal
					if ( str_starts_with($oldNameFullPath, $basepath) && str_starts_with($newNameFullPath, $basepath) ) {
						
						// filtrowanie extention
						$ext = getExt($oldNameFullPath ); 
						if (in_array( $ext, $cmsConfig['upload_files'] ))
						{
							// rename
							if (trim($_POST['filename']) == ''){
							$message .= show_msg ('err', $ERR_file_name);
							} else {
								if (file_exists($currentPath . DS . $newName)){
									$message .= show_msg ('err', $ERR_file_exists);
								} else {
									if (rename( $oldNameFullPath, $newNameFullPath)){
										$message .= show_msg ('msg', $MON_file_edit . ': <strong>' . $oldName . '</strong> ' . $MON_to . ' <strong>' . $newName . '</strong>');
										monitor( $_SESSION['userData']['UID'], $MON_file_edit . ': <strong>' . $oldName . '</strong> ' . $MON_to . ' <strong>' . $newName . '</strong>' , get_ip() );
										$showEditForm = false;
									} else {
										$message .= show_msg ('err', $ERR_contact);
									}
								}
							}
						}
					}
				}
			}
		}
		
		
		//obrona przed path traversal
		if (str_starts_with($currentPath, $basepath))
		{
			//tablica z zawartością katalogu
			$handle = @opendir($currentPath);
			
			$arrFile = array();
			$arrDir = array();
		
			$n = 0;
			$m = 0;
		
			while (false !== ($file = @readdir($handle))){
		
				if ($file != "." && $file != ".."){
		
					if(!is_dir($currentPath . DS . $file)){
						
						$arrFile[$n]['type'] = 'file';
						$arrFile[$n]['filename'] = $file;
						$arrFile[$n]['size'] = formatFileSize(filesize($currentPath . DS . $file));
						$arrFile[$n]['date'] = substr(date('Y-m-d H:i:s', filemtime($currentPath . DS . $file)), 0, 10);
						$arrFile[$n]['icon'] = icon(getExt($file));
						$arrFile[$n]['ext'] = getExt($file);
						$n++;
					
					} else {
					
						$arrDir[$m]['type'] = 'dir';
						$arrDir[$m]['filename'] = $file;
						$arrDir[$m]['size'] = '';
						$arrDir[$m]['date'] = substr(date('Y-m-d H:i:s', filemtime($currentPath . DS . $file)), 0, 10);
						$arrDir[$m]['icon'] = 'fileDirIco';
						$m++;
					
					}
					
				}
			}
			
			asort($arrDir);
			asort($arrFile);
			
			$all = count($arrDir) + count($arrFile);
		}
		//print_r($arrDir);
		//print_r($arrFile);
	
		//echo $url;
		//echo $currentPath;
	}
	else
	{
		$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
		$message .= show_msg ('err', $ERR_priv_access);	
	}
}

?>
