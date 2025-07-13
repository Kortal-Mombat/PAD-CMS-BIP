<?php
	if (!defined('CMS_TEMPL')) {
		header('Location: /error-400');
		exit;
	}
	if (!isset($_GET['id'])) {
		header('Location: /error-404');
		exit;
	}

	$res = new resClass;
	
	$showPage = false;
	$showLoginForm = false;
	$showContactForm = false;
		
	$TEMPL_PATH = CMS_TEMPL . DS . 'page.php';
	
	$_GET['id'] = $_GET['id'] ?? '';

	$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`id`= ?) LIMIT 1";
	$params = array ('id' => $_GET['id']);
	$res->bind_execute( $params, $sql);
	if ($res->numRows != 0) {
		$row = $res->data[0];	
		
		$attrib = unserialize($row['attrib']);	
		$row['show_date'] = substr($row['show_date'], 0, 10);
		$row['start_date'] = substr($row['start_date'], 0, 10);
		$row['stop_date'] = substr($row['stop_date'], 0, 10);	
		
		// ustalenie ilosci artyukulow na stronie
		if (trim($attrib['art_num']) != '' && $attrib['art_num'] >=0 )
		{
			$pageConfig['limit'] = $attrib['art_num'];
			$sql_limit = $pageConfig['limit'];
			
			if ($_GET['s'] > 0)
			{
				$sql_start = $sql_limit * $_GET['s'] - $sql_limit;
			} 
			else
			{
				$_GET['s'] = 1;
				$sql_start = 0;
			}		
		}	
			
		// do templatki
		$rowPage = $row;
		
		// do sciezki okruszkow
		$addcrumbpath = array();
		
		/**
		 * Sprawdzenie przedzialu daty wyswietlania strony
		 */	
		if ( ($row['start_date'] <= $date && $row['stop_date'] >= $date) || ( $row['start_date'] == '0000-00-00' && $row['stop_date'] == '0000-00-00') )
		{
			$showPage = true;	
			$pageName = $row['name'];
			
			if ($row['id'] == $staticPage['kontakt'])
			{
				$showContactForm = true;
			}
					
			// sciezka okruszkow		
			get_crumb ($_GET['id']);
			$addcrumbpath = array_reverse($addcrumbpath);
			$crumbpath = array_merge($crumbpath, $addcrumbpath);
			
			/**
			 * Counter odwiedzin
			 */			
			$sql = "UPDATE `" . $dbTables['pages'] . "` SET `counter` = `counter` + 1  WHERE (`id` = ?) LIMIT 1";
			$params = array ('id' => $_GET['id']);							
			$res->bind_execute( $params, $sql);
			
			/**
			 * Wczytanie meta
			 */	
			if (trim($attrib['meta_title']) != '') {
				$pageTitle = $attrib['meta_title'] . ' - ' . $outSettings['metaTitle'];
			} else {
				$pageTitle = $pageName . ' - ' . $pageTitle;
			}
							
			if (trim($attrib['meta_desciption']) != ''){
				$pageDescription = $attrib['meta_desciption'];
			}

			if (trim($attrib['meta_keywords']) != ''){
				$pageKeywords = $attrib['meta_keywords'];
			}
			
			/**
			 * Sprawdzenie czy strona jest chroniona na haslo
			 */			
			if ($row['protected'] == 1)
			{
				// Jesli nie zalogowany
				if (!$showProtected)
				{
					$showPage = false;
					$showLoginForm = true;

					$message .= show_msg ('err', $ERR['protected_page']);				
				}
				else
				{
					// Jesli nie ma nadanych uprawnien do przegladania stron chronionych
					if ($_SESSION['userPageData']['protected'] != 1)
					{
						$showPage = false;
						$message .= show_msg ('err', $ERR['login_protected'] . ' ' . $ERR['contact']);
					}
				}		
			}
			
			/**
			 * Pobranie articles
			 */		
			$sql = "SELECT COUNT(`" . $dbTables['articles'] . "`.id_art) AS total_records  
						FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
						ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
						WHERE (`" . $dbTables['art_to_pages'] . "`.id_page = ?) AND (active = '1') 
						AND ( (`" . $dbTables['articles'] . "`.start_date <= '".$date."' AND `" . $dbTables['articles'] . "`.stop_date >= '".$date."') OR ( `" . $dbTables['articles'] . "`.start_date = '0000-00-00' AND `" . $dbTables['articles'] . "`.stop_date = '0000-00-00') ) ";
						
			$params = array( 'id_page' => $_GET['id']);
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
						
				$params = array( 'id_page'=> $_GET['id']);
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
					
			/**
			 * Pobranie files
			 */		
			$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) AND (active = '1') ORDER BY pos";	
			$params = array( 
				'id_page'=> $_GET['id'],
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
				'id_page'=> $_GET['id'],
				'type' => 'page'  
				);
			$res->bind_execute( $params, $sql);
			$numPhotos = $res->numRows;
			$outRowPhotos = $res->data;	
			
			/**
			 * Pobranie rejestru
			 */			
			$selectColumn = 'idp';
			$sql = "SELECT * FROM `" . $dbTables['register'] . "` WHERE (`".$selectColumn."`= ?) ORDER BY `id` DESC"; 

			$params = array( $selectColumn => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$numRegister = $res->numRows;
			$outRowRegister = $res->data;	
			
			/**
			 * starsza wersja
			 */				
			if (isset($_GET['idReg']) && $_GET['idReg'] > 0)
			{
				$TEMPL_PATH = CMS_TEMPL . DS . 'article_hist.php';
				
				$sql = "SELECT * FROM `" . $dbTables['register'] . "` WHERE (`id`= ?) "; 
				
				$params = array('id' => $_GET['idReg']);
				$res->bind_execute( $params, $sql);
				$numRegister = $res->numRows;
				if ($numRegister > 0) {
					$oldArticle = $res->data[0];
				}
				
				$message .= show_msg ('err', $ERR['hist_txt'] . ' <a href="index.php?c=page&amp;id='.$row['id'].'">'.$ERR['hist_url'].'</a>');
			}
			
			// pobranie submenu						
			$sql = "SELECT `" . $dbTables['pages'] . "`.* , `" . $dbTables['menu_types'] . "`.menutype,  `" . $dbTables['menu_types'] . "`.active AS mActive
					FROM `" . $dbTables['pages'] . "` LEFT JOIN `" . $dbTables['menu_types'] . "` 
					ON `" . $dbTables['pages'] . "`.menutype=`" . $dbTables['menu_types'] . "`.menutype 
					WHERE (`" . $dbTables['pages'] . "`.ref = ?) AND (`" . $dbTables['pages'] . "`.active = ?) ORDER BY pos";
							
			$params = array (
				'ref' => $_GET['id'],
				'active' => 1
			);
			$res->bind_execute( $params, $sql);
			$numSubmenu = $res->numRows;
			$submenu = $res->data;													
		}
		else
		{
			$pageName = $ERR['show_date'];
			$pageTitle .= ' - ' . $pageName;	
			$message .= show_msg ('err', $ERR['show_date_txt'] );	
		}
	}

?>