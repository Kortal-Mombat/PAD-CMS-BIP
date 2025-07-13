<?php
	$res = new resClass;
	
	$showArticle = true;
	$showLoginForm = false;
		
	$TEMPL_PATH = CMS_TEMPL . DS . 'article.php';
	
	$sql = "SELECT * FROM `" . $dbTables['viewer'] . "` WHERE (`id_viewer`= ?) LIMIT 1";
	$params = array ('id_viewer' => $_GET['id_viewer']);
	$res->bind_execute( $params, $sql);
	$rowView = $res->data[0];	

	$tmp = explode('-', $_GET['id_viewer']);
	$id = $tmp[0];
	$article = unserialize($rowView['text']);
			
	// pobranie pages						
	$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`id`= ?) LIMIT 1";
	$params = array ('id' => $_GET['idp']);
	$res->bind_execute( $params, $sql);
	$page = $res->data[0];	
	
	// do sciezki okruszkow
	$addcrumbpath = array();

	$pageName = $article['name'];

	// sciezka okruszkow
	get_crumb ($_GET['idp']);
	$addcrumbpath = array_reverse($addcrumbpath);
	$crumbpath = array_merge($crumbpath, $addcrumbpath);
	$crumbpath[] = array ('name' => $pageName, 'url' => 'index.php?c=article&amp;id=' . $id);
	
	/**
	 * Wczytanie meta
	 */	
	$pageTitle .= ' - ' . $TXT['page_view'];

			
	/**
	 * Pobranie files
	 */		
	$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) AND (active = '1') ORDER BY pos";	
	$params = array( 
		'id_page'=> $id,
		'type' => 'article'  
		);
	$res->bind_execute( $params, $sql);
	$numFiles = $res->numRows;
	$outRowFiles = $res->data;	
	
	/**
	 * Pobranie zdjec
	 */		
	$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?) AND (active = '1') ORDER BY pos";
			
	$params = array( 
		'id_page'=> $id,
		'type' => 'article'  
		);
	$res->bind_execute( $params, $sql);
	$numPhotos = $res->numRows;
	$outRowPhotos = $res->data;	
?>