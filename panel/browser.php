<?php
if ($showPanel)
{
		$TEMPL_PATH = CMS_TEMPL . DS . 'browser.php';

		$currentPath = '..' . DS . FILES_DIR;

		$showParent = false;

		$_GET['d'] = $_GET['d'] ?? '';
		$_GET['action'] = $_GET['action'] ?? '';

		if ($_GET['d'] != ''){
			$currentPath .= DS . $_GET['d'];
			$_SESSION['userData']['currentPath'] = $currentPath;
			$back = substr($_GET['d'], 0, strrpos($_GET['d'], '/'));
			$showParent = true;
		} else {
			$_GET['d'] = '.';
			$back = $_GET['d'];
		}
		if ($back == ''){
			$back = '.';
		}
		if ($_GET['d'] == '.'){
			$showParent = false;
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
		
	
		//tablica z zawartością katalogu
		$handle = @opendir($currentPath);		
	
		$arrFile = array();
		$arrDir = array();
	
		$n = 0;
		$m = 0;
		
		$fileLength = 16;
	
		while (false !== ($file = @readdir($handle))){
	
			if ($file != "." && $file != ".."){
	
				if(!is_dir($currentPath . DS . $file)){
					
					$arrFile[$n]['type'] = 'file';
					$arrFile[$n]['file'] = $file;
					$arrFile[$n]['filename'] = $file;
					if (strlen($file) > $fileLength)
					{
					    $arrFile[$n]['filename'] = substr($file, 0, $fileLength) . '...';
					}
					$arrFile[$n]['size'] = formatFileSize(filesize($currentPath . DS . $file));
					$arrFile[$n]['date'] = substr(date('Y-m-d H:i:s', filemtime($currentPath . DS . $file)), 0, 10);
					$arrFile[$n]['icon'] = icon(getExt($file));
					$arrFile[$n]['ext'] = getExt($file);
					$n++;
				
				} else {
				
					$arrDir[$m]['type'] = 'dir';
					$arrDir[$m]['file'] = $file;
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
?>