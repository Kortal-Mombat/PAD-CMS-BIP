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
	
	$showArticle = false;
	$showLoginForm = false;
	$oldVersion = false;
		
	$TEMPL_PATH = CMS_TEMPL . DS . 'article.php';
	
	// pobranie artykulu
	$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
			FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
			ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
			WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
			LIMIT 1";
	$params = array ('id_art' => $_GET['id']);
	$res->bind_execute( $params, $sql);
	if ($res->numRows != 0) {
			
		$article = $res->data[0];	
		$attrib = unserialize($article['attrib']);	
			
		$article['show_date'] = substr($article['show_date'], 0, 10);
		$article['start_date'] = substr($article['start_date'], 0, 10);
		$article['stop_date'] = substr($article['stop_date'], 0, 10);	
				
		// pobranie pages						
		$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`id`= ?) LIMIT 1";
		$params = array ('id' => $article['id_page']);
		$res->bind_execute( $params, $sql);
		$page = $res->data[0];	
		
		$page['show_date'] = substr($page['show_date'], 0, 10);
		$page['start_date'] = substr($page['start_date'], 0, 10);
		$page['stop_date'] = substr($page['stop_date'], 0, 10);	
		
		// do sciezki okruszkow
		$addcrumbpath = array();
		
		/**
		 * Sprawdzenie przedzialu daty wyswietlania strony
		 */	
		if ( ($page['start_date'] <= $date && $page['stop_date'] >= $date) || ( $page['start_date'] == '0000-00-00' && $page['stop_date'] == '0000-00-00') )
		{
			if ( ($article['start_date'] <= $date && $article['stop_date'] >= $date) || ( $article['start_date'] == '0000-00-00' && $article['stop_date'] == '0000-00-00') )
			{
				$showArticle = true;	
				$pageName = $article['name'];

				// sciezka okruszkow
				get_crumb ($article['id_page']);
				$addcrumbpath = array_reverse($addcrumbpath);
				$crumbpath = array_merge($crumbpath, $addcrumbpath);
				$crumbpath[] = array ('name' => $pageName, 'url' => 'index.php?c=article&amp;id=' . $article['id_art']);
				
				/**
				 * Counter odwiedzin
				 */			
				$sql = "UPDATE `" . $dbTables['articles'] . "` SET `counter` = `counter` + 1  WHERE (`id_art` = ?) LIMIT 1";
				$params = array ('id_art' => $_GET['id']);							
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
				if ($page['protected'] == 1 || $article['protected'] == 1)
				{
					// Jesli nie zalogowany
					if (!$showProtected)
					{
						$showArticle = false;
						$showLoginForm = true;
		
						$message .= show_msg ('err', $ERR['protected_page']);				
					}
					else
					{
						// Jesli nie ma nadanych uprawnien do przegladania stron chronionych
						if ($_SESSION['userPageData']['protected'] != 1)
						{
							$showArticle= false;
							$message .= show_msg ('err', $ERR['login_protected'] . ' ' . $ERR['contact']);
						}
					}					
				}
				
						
				/**
				 * Pobranie files
				 */		
				$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) AND (active = '1') ORDER BY pos";	
				$params = array( 
					'id_page'=> $_GET['id'],
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
					'id_page'=> $_GET['id'],
					'type' => 'article'  
					);
				$res->bind_execute( $params, $sql);
				$numPhotos = $res->numRows;
				$outRowPhotos = $res->data;	
				
				/**
				 * Pobranie rejestru
				 */			
				$selectColumn = 'idg';
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
					
					$message .= show_msg ('err', $ERR['hist_txt'] . ' <a href="index.php?c=article&amp;id='.$article['id_art'].'">'.$ERR['hist_url'].'</a>');
				}						
			}
			else
			{
				$pageName = $ERR['show_date'];
				$pageTitle = $pageName . ' - ' . $pageTitle;
				$message .= show_msg ('err', $ERR['show_date_txt'] );	
			}					
		}
		else
		{
			$pageName = $ERR['show_date'];
			$pageTitle = $pageName . ' - ' . $pageTitle;
			$message .= show_msg ('err', $ERR['show_date_txt'] );	
		}
	}

?>