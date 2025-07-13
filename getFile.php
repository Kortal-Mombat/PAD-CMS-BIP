<?
	$dir = 'download';
	$vowels = array("/", "\\", "http", "//", ":", "..", ".inc", ".php", ".sql", ".js");
	
	$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_file`= ?) LIMIT 1";
	$params = array ('id_file' => $_GET['id']);
	$res->bind_execute( $params, $sql);
	$row = $res->data[0];	

	$protectFile = false;
	
	// sprawdzenie czy strona chroniona i czy dostepna
	if ($row['type'] == 'page')
	{
		// pobranie pages						
		$sql = "SELECT `" . $dbTables['pages'] . "`.* , `" . $dbTables['menu_types'] . "`.menutype,  `" . $dbTables['menu_types'] . "`.active AS mActive
				FROM `" . $dbTables['pages'] . "` LEFT JOIN `" . $dbTables['menu_types'] . "` 
				ON `" . $dbTables['pages'] . "`.menutype=`" . $dbTables['menu_types'] . "`.menutype 
				WHERE `" . $dbTables['pages'] . "`.id = ?";		
				
		$params = array ('id' => $row['id_page']);

		$res->bind_execute( $params, $sql);
		$page = $res->data[0];				
		if ( $page['active'] == 0 || $page['mActive'] == 0)
		{
			$protectFile = true;
		}
		
		if ( $page['protected'] == 1)	
		{
			// jesli nie zalogowany lub nie uprawnien do stron chronionych
			if (!$showProtected || $_SESSION['userPageData']['protected']!=1)
			{
				header('Location: index.php?c=page&id=' . $page['id'] );
				exit;
			}
			else
			{
				$protectFile == false;
			}
		}										
	}

	// sprawdzenie czy strona chroniona i czy dostepna
	if ($row['type'] == 'article')
	{
		// pobranie artykulu
		$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
				LIMIT 1";	
				
		$params = array ('id_art' => $row['id_page']);

		$res->bind_execute( $params, $sql);
		$article = $res->data[0];				
		if ( $article['active'] == 0 )
		{
			$protectFile = true;
		}
		
		// pobranie pages						
		$sql = "SELECT `" . $dbTables['pages'] . "`.* , `" . $dbTables['menu_types'] . "`.menutype,  `" . $dbTables['menu_types'] . "`.active AS mActive
				FROM `" . $dbTables['pages'] . "` LEFT JOIN `" . $dbTables['menu_types'] . "` 
				ON `" . $dbTables['pages'] . "`.menutype=`" . $dbTables['menu_types'] . "`.menutype 
				WHERE `" . $dbTables['pages'] . "`.id = ?";		
				
		$params = array ('id' => $article['id_page']);

		$res->bind_execute( $params, $sql);
		$page = $res->data[0];				
		if ( $page['active'] == 0 || $page['mActive'] == 0 )
		{
			$protectFile = true;
		}		
		
		if ( $article['protected'] == 1 || $page['protected'] == 1)	
		{
			// jesli nie zalogowany lub nie uprawnien do stron chronionych
			if (!$showProtected || $_SESSION['userPageData']['protected']!=1)
			{
				header('Location: index.php?c=article&id=' . $article['id_art'] );
				exit;
			}
			else
			{
				$protectFile == false;
			}
		}															
	}

	if ($protectFile == false)
	{
		$file = $fname = $row['file'];
		$file = $dir . '/' . str_replace($vowels, '', $file);
		
		
		if (is_file($file))
		{		
			if(file_exists($file))
			{
					$fp = fopen($file,"r");
					$size = filesize($file);
					$contents = fread($fp, filesize($file));
					fclose($fp);
					
					//header("Content-Type: application/octet-stream");
					header('Content-Type: application/x-unknown');
					header("Content-Length: ".$size."");
					header('Content-Disposition: attachment; filename="'.$fname.'"');
					header("Connection: Close");	
					echo $contents;
			}
			else
			{
				header('Location: index.php');
			}
		}
	}
	else
	{
		header('Location: error-403');
	}

?>