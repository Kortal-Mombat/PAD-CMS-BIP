<?
	$res = new resClass;
	
	$showLoginForm = false;
	$showPage = true;
	
	$TEMPL_PATH = CMS_TEMPL . DS . 'page.php';

	$sql = "SELECT * FROM `" . $dbTables['viewer'] . "` WHERE (`id_viewer`= ?) LIMIT 1";
	$params = array ('id_viewer' => $_GET['id_viewer']);
	$res->bind_execute( $params, $sql);
	$rowView = $res->data[0];	

	$tmp = explode('-', $_GET['id_viewer']);
	$id = $tmp[0];
	$row = unserialize($rowView['text']);
	
	// do sciezki okruszkow
	$addcrumbpath = array();

	$pageName = $row['name'];
	
	// sciezka okruszkow		
	get_crumb ($id);
	$addcrumbpath = array_reverse($addcrumbpath);
	$crumbpath = array_merge($crumbpath, $addcrumbpath);
	
	/**
	 * Wczytanie meta
	 */	
	$pageTitle .= ' - ' . $TXT['page_view'];

	/**
	 * Pobranie articles
	 */		
	$sql = "SELECT COUNT(`" . $dbTables['articles'] . "`.id_art) AS total_records  
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (`" . $dbTables['art_to_pages'] . "`.id_page = ?) AND (active = '1') 
				AND ( (`" . $dbTables['articles'] . "`.start_date <= '".$date."' AND `" . $dbTables['articles'] . "`.stop_date >= '".$date."') OR ( `" . $dbTables['articles'] . "`.start_date = '0000-00-00' AND `" . $dbTables['articles'] . "`.stop_date = '0000-00-00') ) ";
				
	$params = array( 'id_page' => $id);
	$res->bind_execute( $params, $sql);
	$numArticles = $res->data[0]['total_records'];		

		
	if ($numArticles > 0)
	{						
		$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (`" . $dbTables['art_to_pages'] . "`.id_page = ?) AND (active = '1') 
				AND ( (`" . $dbTables['articles'] . "`.start_date <= '".$date."' AND `" . $dbTables['articles'] . "`.stop_date >= '".$date."') OR ( `" . $dbTables['articles'] . "`.start_date = '0000-00-00' AND `" . $dbTables['articles'] . "`.stop_date = '0000-00-00') )
				ORDER BY `" . $dbTables['art_to_pages'] . "`.pos  LIMIT ".$sql_start.", ".$sql_limit;	
				
		$params = array( 'id_page'=> $id);
		$res->bind_execute( $params, $sql);
		$outRowArticles = $res->data;	

	
		$pagination = pagination ($numArticles, $pageConfig['limit'], 2, $_GET['s']);	
		
		/**
		 * Pobranie fotki do articles
		 */	
		$photoLead = array();				
		foreach ($outRowArticles as $rowArticle)
		{
			$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?) AND (active = '1') ORDER BY pos LIMIT 1";
			$params = array( 
				'id_page'=> $rowArticle['id_art'],
				'type' => 'article'  
				);
			$res->bind_execute( $params, $sql);
			$photoLead[$rowArticle['id_art']] = $res->data[0];				
		}
	}			 
			
	/**
	 * Pobranie files
	 */		
	$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) AND (active = '1') ORDER BY pos";	
	$params = array( 
		'id_page'=> $id,
		'type' => 'page'  
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
		'type' => 'page'  
		);
	$res->bind_execute( $params, $sql);
	$numPhotos = $res->numRows;
	$outRowPhotos = $res->data;				

?>