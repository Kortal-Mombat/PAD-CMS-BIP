<?
	$res = new resClass;
	
	$TEMPL_PATH = CMS_TEMPL . DS . 'search.php';
	
	$pageName = 'Wyszukiwarka';
	$pageTitle = $pageName . ' - ' . $pageTitle;
		
	$crumbpath[] = array ('name' => 'Wyszukiwarka', 'url' => 'index.php?c=search&amp;kword=' . $_GET['kword']);
	
	setCSS('jquery.ui.theme.css', $css);	
	setCSS('jquery.ui.datepicker.css', $css);
	setJS('jquery-ui.custom.min.js', $js);
	setJS('jquery.ui.datepicker-pl.js', $js);	
	
	if ($_GET['action'] == 'search' || $_GET['action'] == 'searchAdv')
	{
		if ( trim(strlen($_GET['kword'])) < 3 || $_GET['kword'] == $TXT['srch_txt'] )
		{
			$message .= show_msg ('err', $ERR['search_char_min'] );	
		}
		else
		{	
			if ($_GET['action'] == 'searchAdv')
			{
				/**
				 * Dla zaawansowanego
				 */	
				if ($_GET['od'])
					$input_od = $_GET['od'];
				else
					$input_od = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-7, date("Y")));
		
				if ($_GET['do'])
					$input_do = $_GET['do'];
				else
					$input_do = date("Y-m-d");
					
				$sql_txt = "((name LIKE '%".$_GET['kword']."%') OR (text LIKE '%".$_GET['kword']."%') OR (lead_text LIKE '%".$_GET['kword']."%') OR (podmiot LIKE '%".$_GET['kword']."%')) AND ";

				if($_GET['od'])
					$sql_date_od = "(create_date>='".$_GET['od']."') AND ";	
					
				if($_GET['do'])
					$sql_date_do = "(create_date<='".$_GET['do']."') AND ";			

				if($_GET['os_wpr'])
					$sql_wpr = "(wprowadzil LIKE '%".$_GET['os_wpr']."%') AND ";	
					
				if($_GET['os_odp'])
					$sql_author = "(author LIKE '%".$_GET['os_odp']."%') AND ";	
				
				/**
				 * Pobranie page
				 */	
				$sqlPage = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`lang` = ?) AND (active ='1') 
						AND ".$sql_txt . $sql_date_od . $sql_date_do . $sql_wpr . $sql_author . "
						( (start_date <= '".$date."' AND stop_date >= '".$date."') OR ( start_date = '0000-00-00' AND stop_date = '0000-00-00') )		
						ORDER BY pos";
					
				$paramsPage = array( 'lang' => $lang );
				
				/**
				 * Pobranie articles
				 */	
				$sqlArticle = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
						FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
						ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
						WHERE (active = '1') AND ".$sql_txt . $sql_date . $sql_wpr . $sql_author . "
						( (`" . $dbTables['articles'] . "`.start_date <= '".$date."' AND `" . $dbTables['articles'] . "`.stop_date >= '".$date."') OR ( `" . $dbTables['articles'] . "`.start_date = '0000-00-00' AND `" . $dbTables['articles'] . "`.stop_date = '0000-00-00') )
						ORDER BY `" . $dbTables['art_to_pages'] . "`.pos";	
						
				$paramsArticle = array();
				
				/**
				 * Pobranie files
				 */	
				$sqlFiles = "SELECT * FROM `" . $dbTables['files'] . "` WHERE ((name LIKE ?) OR (keywords LIKE ?)) AND ".$sql_date." (active = '1') ORDER BY pos";	
				$paramsFiles = array( 
					'name' => '%' . $_GET['kword'] . '%',
					'keywords' => '%' . $_GET['kword'] . '%'
				);					
			}
			else
			{
				/**
				 * Pobranie page
				 */	
				$sqlPage = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`lang` = ?) AND (active ='1') 
						AND ( (name LIKE ?) OR (lead_text LIKE ?) OR (text LIKE ?) OR (author LIKE ?) )
						AND ( (start_date <= '".$date."' AND stop_date >= '".$date."') OR ( start_date = '0000-00-00' AND stop_date = '0000-00-00') )		
						ORDER BY pos";
					
				$paramsPage = array( 
					'lang' => $lang,
					'name' => '%' . $_GET['kword'] . '%',
					'lead_text' => '%' . $_GET['kword'] . '%',
					'text' => '%' . $_GET['kword'] . '%',
					'author' => '%' . $_GET['kword'] . '%',
				);
				
				/**
				 * Pobranie articles
				 */	
				$sqlArticle = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
						FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
						ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
						WHERE (active = '1') AND ( (name LIKE ?) OR (lead_text LIKE ?) OR (text LIKE ?) OR (author LIKE ?) )
						AND ( (`" . $dbTables['articles'] . "`.start_date <= '".$date."' AND `" . $dbTables['articles'] . "`.stop_date >= '".$date."') OR ( `" . $dbTables['articles'] . "`.start_date = '0000-00-00' AND `" . $dbTables['articles'] . "`.stop_date = '0000-00-00') )
						ORDER BY `" . $dbTables['art_to_pages'] . "`.pos";	
						
				$paramsArticle = array( 
					'name' => '%' . $_GET['kword'] . '%',
					'lead_text' => '%' . $_GET['kword'] . '%',
					'text' => '%' . $_GET['kword'] . '%',
					'author' => '%' . $_GET['kword'] . '%',
				);
				
				/**
				 * Pobranie files
				 */	
				$sqlFiles = "SELECT * FROM `" . $dbTables['files'] . "` WHERE ((name LIKE ?) OR (keywords LIKE ?)) AND (active = '1') ORDER BY pos";	
						
				$paramsFiles = array( 
					'name' => '%' . $_GET['kword'] . '%',
					'keywords' => '%' . $_GET['kword'] . '%'
				);											
			}

			
			/**
			 * Pobranie page
			 */				
			$res->bind_execute( $paramsPage, $sqlPage);
			$outRowPages = $res->data;	
			$numPages = $res->numRows;
			
			/**
			 * Pobranie articles
			 */	
			$res->bind_execute( $paramsArticle, $sqlArticle);
			$outRowArticles = $res->data;	
			$numArticles = $res->numRows;

			/**
			 * Pobranie files
			 */	
			$res->bind_execute( $paramsFiles, $sqlFiles);
			$outRowFiles = $res->data;	
			$numFiles = $res->numRows;

			/**
			 * Laczenie tablic
			 */	
			$searchArray = array();
			$i = 0;
			foreach ($outRowPages as $row)
			{
				$url_title = $protect = $target = $url = '';
				
				if ($row['protected'] == 1)
				{
					$protect = '<span class="protectedPage"></span>';
					$url_title = ' title="' . $TXT['protected_page'] . '"';
				}
				
				if (trim($row['ext_url']) != '')
				{
					if ($row['new_window'] == '1')
					{
						$target = ' target="_blank"';
					}	
					$url_title = ' title="' . $TXT['new_window'] . '"';
					$url = ref_replace($row['ext_url']);					
				}
				else
				{
					$url = 'index.php?c=page&amp;id='. $row['id'];
				}
				
				$searchArray[$i]['id'] = $row['id'];
				$searchArray[$i]['name'] = $row['name'];
				$searchArray[$i]['lead'] = txt_truncate(strip_tags($row['text']), $length = 300, $etc = ' [...]');
				$searchArray[$i]['url'] = '<a href="'. $url .'" ' . $url_title . $target . '>'. $row['name'] . $protect . '</a>';
				$i++;
			}
			
			foreach ($outRowArticles as $row)
			{
				$url_title = $protect = $target = $url = '';
				
				if ($row['protected'] == 1)
				{
					$protect = '<span class="protectedPage"></span>';
					$url_title = ' title="' . $TXT['protected_page'] . '"';
				}
				
				if (trim($row['ext_url']) != '')
				{
					if ($row['new_window'] == '1')
					{
						$target = ' target="_blank"';
					}	
					$url_title = ' title="' . $TXT['new_window'] . '"';
					$url = ref_replace($row['ext_url']);					
				}
				else
				{
					$url = 'index.php?c=article&amp;id=' . $row['id_art'];
				}
				
				$searchArray[$i]['id'] = $row['id_art'];
				$searchArray[$i]['name'] = $row['name'];
				$searchArray[$i]['lead'] = txt_truncate(strip_tags($row['text']), $length = 300, $etc = ' [...]');
				$searchArray[$i]['url'] = '<a href="'. $url .'" ' . $url_title . $target . '>'. $row['name'] . $protect . '</a>';
				$i++;
			}
			
			foreach ($outRowFiles as $row)
			{
				$target = 'target="_blank" ';
				
				if (filesize('download/'.$row['file']) > 5000000)
				{
					$url = 'download/'.$row['file'];
				}
				else
				{
					$url = 'index.php?c=getfile&amp;id='.$row['id_file'];
				}
				if (trim($row['name']) == '')
					$name = $row['file'];
				else
					$name = $row['name'];
					
				$size = file_size('download/'.$row['file']);	
				
				$searchArray[$i]['id'] = $row['id_file'];
				$searchArray[$i]['name'] = $name;
				$searchArray[$i]['lead'] = '';
				$searchArray[$i]['url'] = '<a href="'. $url .'" ' . $url_title . $target . '>'. $row['name'] . $protect . ' <span>('.$size.')</span></a>';
				$i++;
			}
			
			$searchCount = $i;	
			
			if ($searchCount > 0)
			{
				$pagination = pagination ($searchCount, $pageConfig['limit'], 2, $_GET['s']);
				$searchStart = $pageConfig['limit'] * $_GET['s'] - $pageConfig['limit'];
			}
			else
			{
				$message .= show_msg ('err', $ERR['search_result'] );	
			}					
		}
	}
?>