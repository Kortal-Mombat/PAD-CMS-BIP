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
	$_GET['action'] = $_GET['action'] ?? '';
	$_GET['act'] = $_GET['act'] ?? '';
	$_POST['attrib'] = $_POST['attrib'] ?? [];
	if (!isset($_GET['id'])) {
		header('Location: /error-404');
		exit;
	}
    if ($_GET['mt'])	
    { 
		$_SESSION['mt'] = $_GET['mt'];
    }
			
	if ( get_priv_controler('page', $_SESSION['mt']) && get_priv_pages($_GET['idp']) ) 
	{
		if (is_array($_POST))
		{			
			// zamiana na encje dla wybranych zmiennych
			$specialCharsNames = array ('name', 'autor', 'wprowadzil');
			foreach ($_POST as $k => $v)
			{
				if (in_array($k, $specialCharsNames))
				{
					$_POST[$k] = htmlspecialchars(strip_tags($v), ENT_QUOTES);		
				}
			}
		}
			
		if (is_array($_POST['attrib']))
		{			
			// wyczyszczenie meta z niepotrzebnych znaczkow
			foreach ($_POST['attrib'] as $k => $v)
			{
				$_POST['attrib'][$k] = strtr($v, $cmsConfig['replace_char_meta']);
			}	
		}
							
		$TEMPL_PATH = CMS_TEMPL . DS . 'articles.php';
		
		$showList = true;
		$showAddForm = false;
		$showEditForm = false;	
				
		// Potrzebne do flies do rozpoznania typu : page , article
		$_SESSION['type_to_files'] = 'article';
							
		// lista akcji dla ktorych zaladowac dodatkowe css
		$actionCSSList = array ('edit', 'add');
		if (in_array($_GET['action'],$actionCSSList) )	
		{
			setCSS('jquery.ui.theme.css', $css);
			setCSS('jquery.ui.tabs.css', $css);
			setCSS('jquery.ui.datepicker.css', $css);
		}	
						
		$res = new resClass;
			
		// menu type
		$sql = "SELECT * FROM `" . $dbTables['menu_types'] . "` WHERE `menutype` = ? AND `lang` = ? LIMIT 1";
		$params = array (
			'menutype' => $_SESSION['mt'], 
			'lang' => $lang
		);
		$res->bind_execute( $params, $sql);
		$menuType = $res->data[0]['name'];	
			
		// strona / grupa	
		$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE `id` = ? AND `lang` = ? LIMIT 1";
		$params = array (
			'id' => $_GET['idp'], 
			'lang' => $lang
		);
		$res->bind_execute( $params, $sql);
		$pageName = $res->data[0]['name'];	
							
		$pageTitle = 'Artykuły';
			
		$crumbpath[] = array ('name' => $menuType, 'url' => $PHP_SELF . '?c=page');
		$crumbpath[] = array ('name' => $pageName, 'url' =>  $PHP_SELF . '?c=page&amp;id=' . $_GET['idp'].'&amp;action=edit');
		$crumbpath[] = array ('name' => $pageTitle, 'url' => $PHP_SELF . '?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp']);
			
		// potrzebne do sprawdzenia do jakiej grupy nalezy artykul zeby sprawdzic uprawnienia dla tej grupy
		if ($_GET['id'])
		{
			$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
				LIMIT 1";
			$params = array ('id_art' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$rowArticle = $res->data[0];			
		}	
			
		// sprawdzenie czy sa uprawnienia do tej strony			
		if ( get_priv_pages($rowArticle['id_page']) ) 
		{	
			/**
			* Operacje hurtowe
			*/
			if ($_GET['action'] == 'change')
			{
			switch ($_POST['ch_type']) {
						
			// Aktywacja
			case 'show_sel' : 
				foreach($_POST as $k => $v) 
				{
				if (substr($k,0,2)=='m_')
				{
					$sql = "UPDATE `" . $dbTables['articles'] . "` SET active = ? WHERE (`id_art` = ?) LIMIT 1";
					$params = array (
					'active' => 1, 
					'id_art' => $v
					);
					$res->bind_execute( $params, $sql);
				}					
				}
				break;		
						
			// De-Aktywacja
			case 'hide_sel' : 
				foreach($_POST as $k => $v) 
				{
				if (substr($k,0,2)=='m_')
				{
					$sql = "UPDATE `" . $dbTables['articles'] . "` SET active = ? WHERE (`id_art` = ?) LIMIT 1";
					$params = array (
					'active' => 0, 
					'id_art' => $v
					);
					$res->bind_execute( $params, $sql);
				}					
				}
				break;	
						
			// Usuwanie
			case 'del_sel' : 
				foreach($_POST as $k => $v) 
				{
					if (substr($k,0,2)=='m_')
					{
						$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
							FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
							ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
							WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
							LIMIT 1";	
						$params = array ('id_art' => $v);
						$res->bind_execute( $params, $sql);
						$row = $res->data[0];	
						$artName = $row['name'];
						$artPos = $row['pos'];
														
						$sql = "DELETE FROM `" . $dbTables['articles'] . "` WHERE (`id_art` = ?) LIMIT 1";
						$params = array ('id_art' => $v);
						$res->bind_execute( $params, $sql);
						$numRows = $res->numRows;	
									
						if ( $numRows > 0)		
						{		
							$message .= show_msg ('msg', $MSG_del);
							monitor( $_SESSION['userData']['UID'], $MON_article_del . '('.$pageName.') ' .$artName, get_ip() );	
													
							// usuniecie z powiazanych
							$sql = "DELETE FROM `" . $dbTables['art_to_pages'] . "` WHERE (`id_art` = ?) LIMIT 1";
							$params = array ('id_art' => $v);
							$res->bind_execute( $params, $sql);
							$numRows = $res->numRows;	
																
							// zmiana pozycji
							$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (id_page = ?)";
							$params = array (
								'pos' => $artPos, 
								'id_page' => $_GET['idp'], 
							);
							$res->bind_execute( $params, $sql);
													
							/**
							* Usuwanie powiazanych zdjec
							*/			
							$params = array( 
								'id_page'=> $v,
								'type' => $_SESSION['type_to_files']  
							);
														
							$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?) ";	
							$res->bind_execute( $params, $sql);
							foreach ( $res->data as $k )	
							{
								del_file($k['file'], 'photos');	
							}
																
							$sql = "DELETE FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?) ";
							$res->bind_execute( $params, $sql);
							$numRowsPhotos = $res->numRows;				
							
							if ($numRowsPhotos > 0)
							{
								$message .= show_msg ('msg', $MSG_del_photos . $numRowsPhotos);
							}
													
							/**
							* Usuwanie powiazanych plikow do pobrania
							*/	
							$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) ";	
							$res->bind_execute( $params, $sql);
							foreach ( $res->data as $k )	
							{
								del_file($k['file'], 'download');	
							}
																	 
							$sql = "DELETE FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) ";
							$res->bind_execute( $params, $sql);
							$numRowsFiles = $res->numRows;				
							
							/**
							 * Usuwanie wpisow w rejestrze
							 */	
							$sql = "DELETE FROM `" . $dbTables['register'] . "` WHERE (`idg`= ?)";
							$params = array ('idg' => $v);
							$res->bind_execute( $params, $sql);		
																	
							if ($numRowsFiles > 0)
							{
								$message .= show_msg ('msg', $MSG_del_files . $numRowsFiles);
							}
						}								
					}
				}
				break;
						
				// przeniesienie
				case 'move_sel' :
				$movedPos = 0;
								
				foreach($_POST as $k => $v) 
				{
					if (substr($k,0,2) == 'm_')
					{
						$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
							FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
							ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
							WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
							LIMIT 1";
						$params = array ('id_art' => $v);
						$res->bind_execute( $params, $sql);
						$row = $res->data[0];		
						$artPos = $row['pos'];
						$artIdpage = $row['id_page'];
											
						// posortowanie docelowej grupy			
						$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=pos+1 WHERE (pos >= ?) AND (id_page = ?)";
						$params = array (
							'pos' => 1, 
							'id_page' => $_POST['gr']
						);
						$res->bind_execute( $params, $sql);
				
						// przeniesienie	
						$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET id_page = ?, pos = ? WHERE id_art = ?";
						$params = array (
							'id_page' => $_POST['gr'],
							'pos' => 1, 
							'id_art' => $v
						);
						$res->bind_execute( $params, $sql); 
						$numRows = $res->numRows;		
							
						if ( $numRows > 0)	
						{		
							$movedPos++;
						}						
									
						// posortowanie zrodlowej grupy
						$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (id_page = ?)";
						$params = array (
							'pos' => $artPos, 
							'id_page' => $artIdpage
						);
						$res->bind_execute( $params, $sql);	
					}
				}
				if ($movedPos > 0)
				{
					$message .= show_msg ('msg', $MSG_move);
					monitor( $_SESSION['userData']['UID'], $pageName . ' - ' . $MON_article_move . '('.$movedPos.') ' , get_ip() );
								
				}
				break;
				}
			}
				
				/**
				 * Dodanie 
				 */
				if ($_GET['action'] == 'add')
				{
					$err = '';
					
					$crumbpath[] = array ('name' => 'Dodaj artykuł', 'url' => $PHP_SELF . '?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp'] . '&amp;action=' . $_GET['action']);
					
					$pageTitle = 'Dodaj artykuł';
					$showAddForm = true;
					$showList = false;
					
					if ($_GET['act'] == 'addPoz')
					{
						if ($_POST['new_window'] == '') 
							$_POST['new_window'] = 0;
						else
							$_POST['new_window'] = 1;
						
						$modified_date = date("Y-m-d H:i:s");	
									
						if (!$_POST['name']){
							$err .= show_msg ('err', $ERR_title);
						}

						if (!$_POST['autor']){
							$err .= show_msg ('err', 'Wpisz osobę sporządzającą dokument w zakładce Ustawienia.');
						}

						if (!$_POST['podmiot']){
							$err .= show_msg ('err', 'Wpisz nazwę podmiotu udostępniającego informację w zakładce Ustawienia.');
						}
						
						$message = $err;
						
						if (!$err)
						{
							$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables['art_to_pages'] . "` WHERE (id_page = ?) ";
							$params = array ( 'id_page' => $_GET['idp'] );				
							$res->bind_execute( $params, $sql);
							$maxPos = $res->data[0]['maxPos'] + 1;
							
							if ($_POST['pos']<=0 || trim($_POST['pos'])=='' || !is_numeric($_POST['pos'])) {
								$_POST['pos'] = 1;
							}
											
							if ($_POST['pos']>$maxPos){
								$_POST['pos'] = $maxPos;
							}	
						
							$sql = "INSERT INTO `" . $dbTables['articles'] . "` VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?, ?, ?, ?, ?, 'dynamic', ?, ?, ?, ?, ?, ?, '0')";
							$params = array (
										'name' => strip_tags($_POST['name']), 
										'url_name' =>  strip_tags(trans_url_name($_POST['url_name'])),
										'lead_text' => $_POST['lead_text'], 
										'text' => $_POST['text'],
										'author' => strip_tags($_POST['autor']),
										'wprowadzil' => strip_tags($_POST['wprowadzil']),
										'podmiot' => strip_tags($_POST['podmiot']),
										'attrib' => serialize($_POST['attrib']), 
										'ext_url' => $_POST['ext_url'],
										'new_window' => $_POST['new_window'],
										'show_on_main' => $_POST['show_on_main'],
										'highlight' => $_POST['highlight'],
										'protected' => $_POST['protected'],
										'ingallery' => $_POST['ingallery'],
										'lang' => $lang,
										'create_date' => $modified_date,
										'modified_date' => $modified_date,
										'show_date' => $_POST['show_date'],
										'start_date' => $_POST['start_date'],
										'stop_date' => $_POST['stop_date']
										);							
							
							$res->bind_execute( $params, $sql);
							$numRows = $res->numRows;		
							$idArticle = $res->lastID; 
	
							
							if ( $numRows > 0)	
							{		
								$message .= show_msg ('msg', $MSG_add);
								monitor( $_SESSION['userData']['UID'], $MON_article_add . '('.$pageName.') ' .$_POST['name'] , get_ip() );
								
								add_to_register ($idArticle, $_SESSION['type_to_files']);
								
								// posortowanie
								$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=pos+1 WHERE (pos>=?) AND (id_page=?)";
								$params = array (
										'pos' => $_POST['pos'],
										'id_page' => $_GET['idp'], 
										);							
								$res->bind_execute( $params, $sql);
								
								// dodanie					
								$sql = "INSERT INTO `" . $dbTables['art_to_pages'] . "` VALUES (?, ?, ?)";
								$params = array (
										'id_page' => $_GET['idp'], 
										'id_art' => $idArticle, 
										'pos' => $_POST['pos'],
										);							
								$res->bind_execute( $params, $sql);
								$numRows = $res->numRows;	
								
								if ($_POST['save']) {
									$showAddForm = false;
									$showList = true;
								}				
							}							
						}
					}
			
					if ($_POST['saveAdd'] || !$_POST['save']) {
						$showAddForm = true;
					}
				}
			
					
				/**
				 * Edycja
				 */
				if ($_GET['action'] == 'edit')
				{
					$pageTitle = 'Edytuj artykuł';
	
					$showView = false;
					$showEditForm = true;	
					$showList = false;	
					
					if ($_POST['new_window'] == '') 
						$_POST['new_window'] = 0;
					else
						$_POST['new_window'] = 1;
					
					if ($_GET['act'] == 'editPoz' && !$_POST['view'])
					{
						$modified_date = date("Y-m-d H:i:s");	
																
						if (!$_POST['name']){
							$err .= show_msg ('err', $ERR_title);
						}

						if (!$_POST['autor']){
							$err .= show_msg ('err', 'Wpisz osobę sporządzającą dokument w zakładce Ustawienia.');
						}

						if (!$_POST['podmiot']){
							$err .= show_msg ('err', 'Wpisz nazwę podmiotu udostępniającego informację w zakładce Ustawienia.');
						}
						
						$message = $err;
						
						if (!$err)
						{
							$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables['art_to_pages'] . "` WHERE (id_page = ?) ";
							$params = array ( 'id_page' => $_GET['idp'] );				
							$res->bind_execute( $params, $sql);
							$maxPos = $res->data[0]['maxPos'];
							
							if ($_POST['pos']<=0 || trim($_POST['pos'])=='' || !is_numeric($_POST['pos'])) {
								$_POST['pos'] = 1;
							}
											
							if ($_POST['pos']>$maxPos){
								$_POST['pos'] = $maxPos;
							}	
							
							if($_POST['old_pos'] >= $_POST['pos']) 
							{
								// posortowanie
								$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=pos+1 WHERE (pos>=?) AND (pos<?) AND (id_page=?)";
								$params = array (
										'pos' => $_POST['pos'],
										'old_pos' => $_POST['old_pos'],
										'id_page' => $_GET['idp'], 
										);							
								$res->bind_execute( $params, $sql);						
							} 
							else 
							{
								// posortowanie
								$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=pos-1 WHERE (pos>?) AND (pos<=?) AND (id_page=?)";
								$params = array (
										'old_pos' => $_POST['old_pos'],
										'pos' => $_POST['pos'],
										'id_page' => $_GET['idp'], 
										);							
								$res->bind_execute( $params, $sql);						
							}
								
							// aktualizacja pozycji
							$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=? WHERE (id_art=?)  LIMIT 1";
							$params = array (
									'pos' => $_POST['pos'],
									'id_art' => $_GET['id'] 
									);							
							$res->bind_execute( $params, $sql);	
																		
							$sql = "UPDATE `" . $dbTables['articles'] . "` SET 
									name = ?, url_name = ?, lead_text = ?, text = ?, author = ?, wprowadzil = ?, podmiot = ?, attrib = ?, ext_url = ?, new_window = ?, show_on_main = ?, highlight = ?, 
									protected = ?,	ingallery = ?, modified_date = ?, show_date = ?, start_date = ?, stop_date = ? WHERE (`id_art` = ?) LIMIT 1";
			
							$params = array (
										'name' => strip_tags($_POST['name']), 
										'url_name' =>  strip_tags(trans_url_name($_POST['url_name'])),
										'lead_text' => $_POST['lead_text'], 
										'text' => $_POST['text'],
										'author' => strip_tags($_POST['autor']),
										'wprowadzil' => strip_tags($_POST['wprowadzil']),
										'podmiot' => strip_tags($_POST['podmiot']),
										'attrib' => serialize($_POST['attrib']), 
										'ext_url' => $_POST['ext_url'],
										'new_window' => $_POST['new_window'],
										'show_on_main' => $_POST['show_on_main'],
										'highlight' => $_POST['highlight'],
										'protected' => $_POST['protected'],
										'ingallery' => $_POST['ingallery'],
										'modified_date' => $modified_date,
										'show_date' => $_POST['show_date'],
										'start_date' => $_POST['start_date'],
										'stop_date' => $_POST['stop_date'],
										'id_art' => $_GET['id'] 
										);								
							
							$res->bind_execute( $params, $sql);
							$numRows = $res->numRows;
			
							if ( $numRows > 0)	
							{		
								$message .= show_msg ('msg', $MSG_edit);
								monitor( $_SESSION['userData']['UID'], $MON_article_edit . '('.$pageName.') ' .$_POST['name'] , get_ip() );
								
								add_to_register ($_GET['id'], $_SESSION['type_to_files']);
								
								if ($_POST['saveEdit']) {
									$showEditForm = false;
									$showList = true;
								}						
							}				
						}
					}
					
					$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
							FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
							ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
							WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
							LIMIT 1";
					$params = array ('id_art' => $_GET['id']);
					$res->bind_execute( $params, $sql);
					$row = $res->data[0];	
					$artName = $row['name'];
					
					$crumbpath[] = array ('name' => $artName, 'url' => '');			
					$crumbpath[] = array ('name' => 'Edytuj artykuł', 'url' => $PHP_SELF . '?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp'] . '&amp;action=' . $_GET['action'].'&amp;id=' . $_GET['id']);
		
					$attrib = unserialize($row['attrib']);	
					
					$row['show_date'] = substr($row['show_date'], 0, 10);
					$row['start_date'] = substr($row['start_date'], 0, 10);
					$row['stop_date'] = substr($row['stop_date'], 0, 10);	
										
					// podglad strony
					if ($_POST['view'])
					{				
						$row['name'] = stripslashes($_POST['name']);
						$row['lead_text'] = stripslashes($_POST['lead_text']);
						$row['text'] = stripslashes($_POST['text']);
						$row['ext_url'] = $_POST['ext_url'];
						$row['new_window'] = $_POST['new_window'];
						$attrib['art_num'] = $_POST['attrib']['art_num'];
						$attrib['meta_title'] =  stripslashes($_POST['attrib']['meta_title']);
						$attrib['meta_desciption'] =  stripslashes($_POST['attrib']['meta_desciption']);
						$attrib['meta_keywords'] =  stripslashes($_POST['attrib']['meta_keywords']);
						$row['showonmain'] = $_POST['showonmain'];
						$row['highlight'] = $_POST['highlight'];
						$row['ingallery'] = $_POST['ingallery'];
						$row['protected'] = $_POST['protected'];
						$row['author'] =  stripslashes($_POST['autor']);
						$row['wprowadzil'] = stripslashes($_POST['wprowadzil']);
						$row['podmiot'] = stripslashes($_POST['podmiot']);						
						$row['show_date'] = $_POST['show_date'];
						$row['start_date'] = $_POST['start_date'];
						$row['stop_date'] = $_POST['stop_date'];
						
						$text['name'] = strtr($row['name'], $cmsConfig['replace_char_toview']);
						$text['lead_text'] = strtr($row['lead_text'], $cmsConfig['replace_char_toview']);
						$text['text'] = strtr($row['text'], $cmsConfig['replace_char_toview']);
						$text['author'] = strtr($row['author'], $cmsConfig['replace_char_toview']);
						
						$sql = "INSERT INTO `" . $dbTables['viewer'] . "` VALUES (?, ?)";
		
						// potrzebne do przesłania do kontrolera
						$idViewer = $_GET['id'].'-'.time();
						
						$params = array (
									'id_viewer' => $idViewer, 
									'text' => serialize($text)
									);							
						$res->bind_execute( $params, $sql);
						
						$showView = true;
					}
				}
				
				/**
				 * Usuwanie
				 */
				if ($_GET['action'] == 'delete')
				{
					$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
							FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
							ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
							WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
							LIMIT 1";	
					$params = array ('id_art' => $_GET['id']);
					$res->bind_execute( $params, $sql);
					$row = $res->data[0];	
					$artName = $row['name'];
					$artPos = $row['pos'];
								
					$sql = "DELETE FROM `" . $dbTables['articles'] . "` WHERE (`id_art` = ?) LIMIT 1";
					$params = array ('id_art' => $_GET['id']);
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;	
			
					if ( $numRows > 0)		
					{		
						$message .= show_msg ('msg', $MSG_del);
						monitor( $_SESSION['userData']['UID'], $MON_article_del . '('.$pageName.') ' .$artName, get_ip() );	
						
						// usuniecie z powiazanych
						$sql = "DELETE FROM `" . $dbTables['art_to_pages'] . "` WHERE (`id_art` = ?) LIMIT 1";
						$params = array ('id_art' => $_GET['id']);
						$res->bind_execute( $params, $sql);
						$numRows = $res->numRows;	
									
						// zmiana pozycji
						$sql = "UPDATE `" . $dbTables['art_to_pages'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (id_page = ?)";
						$params = array (
									'pos' => $artPos, 
									'id_page' => $_GET['idp'], 
									);
						$res->bind_execute( $params, $sql);
						
						/**
						 * Usuwanie powiazanych zdjec
						 */			
						$params = array( 
							'id_page'=> $_GET['id'],
							'type' => $_SESSION['type_to_files']  
							);
							
						$sql = "SELECT * FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?) ";	
						$res->bind_execute( $params, $sql);
						foreach ( $res->data as $k )	
						{
							del_file($k['file'], 'photos');	
						}
										
						$sql = "DELETE FROM `" . $dbTables['photos'] . "` WHERE (`id_page`= ?) AND (type = ?) ";
						$res->bind_execute( $params, $sql);
						$numRowsPhotos = $res->numRows;				
						if ($numRowsPhotos > 0) {
							$message .= show_msg ('msg', $MSG_del_photos . $numRowsPhotos);
						}
						
						/**
						 * Usuwanie powiazanych plikow do pobrania
						 */	
						$sql = "SELECT * FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) ";	
						$res->bind_execute( $params, $sql);
						foreach ( $res->data as $k )	
						{
							del_file($k['file'], 'download');	
						}
										 
						$sql = "DELETE FROM `" . $dbTables['files'] . "` WHERE (`id_page`= ?) AND (type = ?) ";
						$res->bind_execute( $params, $sql);
						$numRowsFiles = $res->numRows;				
						if ($numRowsFiles > 0) {
							$message .= show_msg ('msg', $MSG_del_files . $numRowsFiles);				 
						}
						
						/**
						 * Usuwanie wpisow w rejestrze
						 */	
						$sql = "DELETE FROM `" . $dbTables['register'] . "` WHERE (`idg`= ?)";
						$params = array ('idg' => $_GET['id']);
						$res->bind_execute( $params, $sql);						
					}	
				}
				
				/**
				 * Przesuniecie pozycji do gory
				 */			
				if ($_GET['action'] == 'posTop')
				{
					$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
							FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
							ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
							WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
							LIMIT 1";
					$params = array ('id_art' => $_GET['id']);
					$res->bind_execute( $params, $sql);
					$row = $res->data[0];
					
					$sql = "UPDATE `" .$dbTables['art_to_pages'] . "` SET pos=pos+1 WHERE (pos = ?) AND (id_page= ?)";
					$params = array (
									'pos' => $row['pos']-1, 
									'id_page' => $_GET['idp']
									);
					$res->bind_execute( $params, $sql);
					
					$sql = "UPDATE `" .$dbTables['art_to_pages'] . "` SET pos=pos-1 WHERE (id_art= ?)";
					$params = array (
									'id_art' => $_GET['id']
									);
					$res->bind_execute( $params, $sql);
				}	

			
			
				/**
				 * Przesuniecie pozycji na dol
				 */		
				if ($_GET['action'] == 'posBot')
				{
					$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
							FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
							ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
							WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
							LIMIT 1";
					$params = array ('id_art' => $_GET['id']);
					$res->bind_execute( $params, $sql);
					$row = $res->data[0];
					
					$sql = "UPDATE `" .$dbTables['art_to_pages'] . "` SET pos=pos-1 WHERE (pos = ?) AND (id_page= ?)";
					$params = array (
									'pos' => $row['pos']+1, 
									'id_page' => $_GET['idp']
									);
					$res->bind_execute( $params, $sql);
					
					$sql = "UPDATE `" .$dbTables['art_to_pages'] . "` SET pos=pos+1 WHERE (id_art= ?)";
					$params = array (
									'id_art' => $_GET['id']
									);
					$res->bind_execute( $params, $sql);
				}	

				
										
				/**
				 * De-Aktywacja
				 */
				if ($_GET['action'] == 'noactive')
				{
					$sql = "UPDATE `" . $dbTables['articles'] . "` SET active = ? WHERE (`id_art` = ?) LIMIT 1";
					$params = array (
									'active' => 0, 
									'id_art' => $_GET['id']
									);
					$res->bind_execute( $params, $sql);
				}
				
				/**
				 * Aktywacja
				 */
				if ($_GET['action'] == 'active')
				{
					$sql = "UPDATE `" . $dbTables['articles'] . "` SET active = ? WHERE (`id_art` = ?) LIMIT 1";
					$params = array (
									'active' => 1, 
									'id_art' => $_GET['id']
									);
					$res->bind_execute( $params, $sql);
				}
				
				/**
				 * kopiowanie zdjec
				 */				
				if ($_GET['act'] == 'uploadPhotos') 
				{
					for ($i=1; $i<=$_GET['filesNum']; $i++)
					{
						$idTable = 'photos';
						$idPage = $_GET['id'];
						$idType = $_SESSION['type_to_files'];
						$opis = $_POST['opis'.$i];
						
						$uploadPath = '';
						
						$mini = 1;
						$miniWidth = $imageConfig['miniWidth'];
						$miniHeight = $imageConfig['miniHeight'];
						$proportional = $imageConfig['proportional'];
						$jpgCompression = $imageConfig['jpgCompression'];;
						
						$fileName = trans_url_name_may($_FILES['file'.$i]['name']);
						$fileName = trim(basename($fileName));
						
						$uploadPath = '..' . DS . 'files' . DS . 'pl';
						$uploadPathFile = $uploadPath . DS . $fileName;
						
						if (($_FILES['file'.$i]['type'] != "image/pjpeg") && ($_FILES['file'.$i]['type'] != "image/jpeg"))
						{
							$message .= show_msg ('err', 'Plik ['.$_FILES['file'.$i]['name'].'] jest w niedozwolonym formacie. Dozwolone są tylko pliki JPG.');	
						}
						else
						{
							$uploadPathFile = checkFileExists($uploadPathFile);
							
							$fileName = basename($uploadPathFile);
							
							if (move_uploaded_file($_FILES['file'.$i]['tmp_name'], $uploadPathFile) )
							{
								imagemax($fileName, $imageConfig['maxWidth'], $imageConfig['maxWidth'], $uploadPath, $uploadPath, $jpgCompression);
																
								$message .= show_msg ('msg', 'Plik o nazwie ' . $_FILES['file'.$i]['name'] . ' został skopiowany.');
								if (trim($opis) == '')
								{
									$message .= show_msg ('err', 'Plik ' . $_FILES['file'.$i]['name'] . ': ' . $ERR_no_photo_alt);
								}								
								monitor( $_SESSION['userData']['UID'], 'Artykuły - Zdjęcie ' .  $_FILES['file'.$i]['name'] . ' - zostało skopiowane.' , get_ip() );
							}

							$res = new resClass;
							
							$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables[$idTable] . "` WHERE (`id_page` = ?) AND (`type` = ?)";
							$params = array(
								'id_page'	=> $idPage,
								'type'	=> $idType
							);
						
							$res -> bind_execute($params, $sql);
							$maxPos = $res->data[0]['maxPos'] + 1;
							
							$sql = "INSERT INTO `" . $dbTables[$idTable] . "` VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)";
							$params = array(
								'id_page' => $idPage,
								'type' => $idType,
								'pos' => $maxPos,
								'name' => $opis,
								'file' => $fileName,
								'active' => 1,
								'keywords' => '',
								'data' => date("Y-m-d")
							);
							$res -> bind_execute($params, $sql);
						
							
							if ($mini == 1)
							{
								if ($proportional == 1)
								{
									imagemax($fileName, $miniWidth, $miniHeight, $uploadPath . DS . 'mini', $uploadPath, $jpgCompression);
								} 
								else
								{
								   imagemini($fileName, $miniWidth, $miniHeight, $uploadPath . DS . 'mini', $uploadPath, $jpgCompression);
								}
							}   
						} 
					}
				}		
				
				/**
				 * kopiowanie plikow
				 */				
				if ($_GET['act'] == 'uploadFiles') 
				{
					for ($i=1; $i<=$_GET['filesNum']; $i++)
					{
						$idTable = 'files';
						$idPage = $_GET['id'];
						$idType = $_SESSION['type_to_files'];
						$opis = $_POST['opis'.$i];
						
						$uploadPath = '';
						
						$fileName = trans_url_name_may($_FILES['file'.$i]['name']);
						$fileName = trim(basename($fileName));
						
						$uploadPath = '..' . DS . 'download';
						$uploadPathFile = $uploadPath . DS . $fileName;
						
						$ext = getExt($fileName);
						
						if (in_array($ext,$cmsConfig['upload_files']))
						{
							$uploadPathFile = checkFileExists($uploadPathFile);
							
							$fileName = basename($uploadPathFile);
							
							if (move_uploaded_file($_FILES['file'.$i]['tmp_name'], $uploadPathFile) )
							{
								$message .= show_msg ('msg', 'Plik o nazwie ' . $_FILES['file'.$i]['name'] . ' został skopiowany.');
								monitor( $_SESSION['userData']['UID'], 'Artykuły - Plik ' .  $_FILES['file'.$i]['name'] . ' - został skopiowany.' , get_ip() );
							}
							
							$res = new resClass;
							
							$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables[$idTable] . "` WHERE (`id_page` = ?) AND (`type` = ?)";
							$params = array(
								'id_page'	=> $idPage,
								'type'	=> $idType
							);
						
							$res -> bind_execute($params, $sql);
							$maxPos = $res->data[0]['maxPos'] + 1;
							
							$sql = "INSERT INTO `" . $dbTables[$idTable] . "` VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)";
							$params = array(
								'id_page' => $idPage,
								'type' => $idType,
								'pos' => $maxPos,
								'name' => $opis,
								'file' => $fileName,
								'active' => 1,
								'keywords' => '',
								'data' => date("Y-m-d")
							);
							$res -> bind_execute($params, $sql);
							
						} 
						else
						{
							$message .= show_msg ('err', 'Plik ['.$_FILES['file'.$i]['name'].'] jest w niedozwolonym formacie.');	
						}
					}
				}									
			}
			else
			{
				$showList = false;
				$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
				$message .= show_msg ('err', $ERR_priv_access);				
			}	
									
			/**
			 * Pobranie articles
			 */
			if ($showList)
			{		 
				$sql = "SELECT COUNT(id_art) AS total_records FROM `" . $dbTables['art_to_pages'] . "` WHERE (`id_page`= ?)";
				$params = array( 'id_page' => $_GET['idp']);
				$res->bind_execute( $params, $sql);
				
				
				$r = $res->data[0];	
				$numRows = $r['total_records'];		
			
				/* Ilość artykułów */
				if ($_GET['action'] == 'number')
				{
				    $sql_start = 0;
				    $sql_limit = $_POST['number'];
				    if ($_POST['number'] == 'wszystkie')
				    {
					$sql_limit = $numRows;
				    }
				}
				
			if ($numRows > 0)
			{						
			$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" .$dbTables['articles'] . "`.* 
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (`" . $dbTables['art_to_pages'] . "`.id_page = ?)
				ORDER BY `" . $dbTables['art_to_pages'] . "`.pos  LIMIT ".$sql_start.", ".$sql_limit;
							
			$params = array( 'id_page'=> $_GET['idp']);
			$res->bind_execute( $params, $sql);
			$outRow = $res->data;	
			
			if ($_POST['number'] == 'wszystkie')
			{
			    $sql_limit = 'wszystkie';
			}
	
			$pagination = pagination ($numRows, $cmsConfig['limit'], 2, $_GET['s']);
			
			$sql = "SELECT `" . $dbTables['menu_types'] . "`.*
					FROM `" . $dbTables['menu_types'] . "` ";
			$params = array();
			$res->bind_execute( $params, $sql );
			$outMenu = $res->data;
			$numMenu = $res->numRows;			
			
			$menuTree = array();
			foreach($outMenu as $value)
			{
					
				// pobranie drzewka grup
				$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`lang` = ?) ORDER BY pos";
				$params = array (
					'menutype' => $value['menutype'],
					'lang' => $lang
				);
				$res->bind_execute( $params, $sql);
				$menuTree[] = $res->data;
			}
			//debug ($menuTree);
			}
			else
			{
				$message .= show_msg ('info', 'Brak przypisanych artykułów.');	
			}						
		}
	} 
	else
    {
		$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
		$message .= show_msg ('err', $ERR_priv_access);	
    }
}
?>