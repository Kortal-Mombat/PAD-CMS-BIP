<?php
	$TEMPL_PATH = CMS_TEMPL . DS . 'start.php';

	$res = new resClass;
 
	$pageName = $TXT['but_start'];
 
	$_GET['id'] = $_GET['id'] ?? 0;
	/**
	 * Pobranie articles
	 */		
	$sql = "SELECT COUNT(`" . $dbTables['articles'] . "`.id_art) AS total_records  
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (active = '1') 
				AND ( (`" . $dbTables['articles'] . "`.start_date <= '".$date."' AND `" . $dbTables['articles'] . "`.stop_date >= '".$date."') OR ( `" . $dbTables['articles'] . "`.start_date = '0000-00-00' AND `" . $dbTables['articles'] . "`.stop_date = '0000-00-00') ) ";
				
	$params = array( 'id_page' => $_GET['id']);
	$params = array();
	$res->bind_execute( $params, $sql);
	$numArticles = $res->data[0]['total_records'];		
	
	if ($numArticles > 0)
	{						
		$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (active = '1') 
				AND ( (`" . $dbTables['articles'] . "`.start_date <= '".$date."' AND `" . $dbTables['articles'] . "`.stop_date >= '".$date."') OR ( `" . $dbTables['articles'] . "`.start_date = '0000-00-00' AND `" . $dbTables['articles'] . "`.stop_date = '0000-00-00') )
				ORDER BY `" . $dbTables['articles'] . "`.show_date DESC, `" . $dbTables['articles'] . "`.id_art DESC  LIMIT ".$sql_start.", ".$sql_limit;	
				
		$params = array();
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
			if ($res->numRows != 0) {
				$photoLead[$rowArticle['id_art']] = $res->data[0];				
			}else {
				$photoLead[$rowArticle['id_art']] = null;				
			}
		}
	}			
?>