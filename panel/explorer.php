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
	if (get_priv_controler($_GET['c']) && isset($_GET['filename']) && isset($_GET['action']))
	{	
		$_GET['act'] = $_GET['act'] ?? '';
		$_GET['filename'] = str_replace('|', '.', $_GET['filename']);
		
	
		$TEMPL_PATH = CMS_TEMPL . DS . 'explorer.php';
		$pageTitle .= $TXT_menu_files;
		
		$crumbpath[] = array ('name' => $TXT_menu_files, 'url' => $PHP_SELF . '?c=' . $_GET['c']);	
		
		$currentPath = '..' . DS . FILES_DIR;
			
		if ($_GET['d'] != ''){		
			$currentPath .= DS . $_GET['d'];
			$_SESSION['userData']['currentPath'] = $currentPath;
			$back = substr($_GET['d'], 0, strrpos($_GET['d'], '/'));
		} else {
			$_GET['d'] = '.';
			$back = $GET['d'];
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
			
			if (file_exists($currentPath . DS . $_GET['filename'])){
				removeRecurenceDir ( $currentPath . DS . $_GET['filename'] );
				$message .= show_msg ('msg', $MSG_dir_del);
				monitor( $_SESSION['userData']['UID'], $MON_dir_del . '<strong> ' . $_GET['filename'] . '</strong>', get_ip() );
			} else {
				$message .= show_msg ('err', $ERR_dir_noexists );
			}
			//
		}
		
		//usunięcie pliku
		if ($_GET['action'] == 'deleteFile'){
			$filename = str_replace('|', '.', $_GET['filename']);
			if (file_exists($currentPath . DS . $filename)){
				unlink($currentPath . DS . $filename);
				$message .= show_msg ('msg', $MSG_file_del);
				monitor( $_SESSION['userData']['UID'], $MON_file_del . '<strong> ' . $filename . '</strong>', get_ip() );			
			} else {
				$message .= show_msg ('err', $ERR_file_noexists );
			}
		}
		
		//dodanie katalogu
		if ($_GET['action'] == 'add'){
			$showAddForm = true;
			
			if ($_GET['act'] == 'addDir'){
				$newDir = trans_url_name(trim($_POST['filename']), $transPL);
				if (trim($newDir) == ''){
					$message .= show_msg ('err', $ERR_dir_name);
				} else {
					if (file_exists($currentPath . DS . $newDir)){
						$message .= show_msg ('err', $ERR_dir_exists . ':  ' . $_GET['d'] . '.');
					} else {
						if(mkdir($currentPath . DS . $newDir, 0777)){
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
		
		//edycja obrazka
		if ($_GET['action'] == 'editImage'){
			
			$newFile = 'temp_'.str_replace('|', '.', $_GET['image']);
			$newFileRename = substr($newFile, 5);
			$oldFile = str_replace('|', '.', $_GET['image']);
			@unlink($currentPath . DS . $oldFile);
			rename($currentPath . DS . $newFile, $currentPath . DS . $newFileRename);
			$message .= show_msg ('msg', 'Plik ' . $newFileRename . ' został poprawnie zapisany.');
			monitor( $_SESSION['userData']['UID'], 'Obróbka obrazu: '.$newFileRename , get_ip() );
		}
		
		//dodanie plików

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
				$newDir = trans_url_name(trim($_POST['filename']), $transPL);
				if (trim($newDir) == ''){
					$message .= show_msg ('err', $ERR_dir_name);
				} else {
					if (file_exists($currentPath . DS . $newDir)){
						$message .= show_msg ('err', $ERR_dir_exists . ':  ' . $_GET['d'] . '.');
					} else {
						if (rename( $currentPath . DS . $oldDir, $currentPath . DS . $newDir)){
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
						for ($j=0; $j<count($cmsConfig['upload_files']); $j++)
						{
							if ($cmsConfig['upload_files'][$j] == $ext)
							{
								$stat = 1;
								if(file_exists($currentPath.'/'.$_FILES['file'.$i]['name']))
								{
									$message .= show_msg ('err', 'Plik o nazwie ' . $_FILES['file'.$i]['name'] . ' już istnieje.');
								}
								else if (move_uploaded_file($_FILES['file'.$i]['tmp_name'], $currentPath.'/'.trans_url_name_may($_FILES['file'.$i]['name'])))
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
					
						if ($stat==0)
						{
							$message .= show_msg ('err', 'Plik ['.$_FILES['file'.$i]['name'].'] jest w niedozwolonym formacie.');	
						}									
					}
				}
			}			
		}
		
		//edycja nazwy
		if ($_GET['action'] == 'editFile')
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
				
				$newName = $_POST['filename'] . '.' .$_POST['extension'];
				$oldName = $_POST['oldName'];
				
				if (trim($_POST['filename']) == ''){
					$message .= show_msg ('err', $ERR_file_name);
				} else {
					if (file_exists($currentPath . DS . $newName)){
						$message .= show_msg ('err', $ERR_file_exists);
					} else {
						if (rename( $currentPath . DS . $oldName, $currentPath . DS . $newName)){
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