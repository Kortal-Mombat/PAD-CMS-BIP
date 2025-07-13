<?php
if ($showPanel)
{	
    if ($_GET['mt'])	
    { 
		$_SESSION['mt'] = $_GET['mt'];
    }
	
	if ( $_SESSION['mt'] == 'ft') {
		$depthTree = 0;
	}
	if ( $_SESSION['mt'] == 'tm') {
		$depthTree = 1;
	}
	if ( $_SESSION['mt'] == 'mg') {
		$depthTree = 1;
	}	
	

    if (get_priv_controler($_GET['c'], $_SESSION['mt']))
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
		
	$TEMPL_PATH = CMS_TEMPL . DS . 'page.php';
	
	$showList = true;
	$showAddForm = false;
	$showEditForm = false;	
		
	// Potrzebne do flies do rozpoznania typu : page , articles
	$_SESSION['type_to_files'] = 'page';
				
	// lista akcji dla ktorych zaladowac dodatkowe css
	$actionCSSList = array ('edit', 'add');
	if (in_array($_GET['action'],$actionCSSList))	
	{
	    setCSS('jquery.ui.theme.css', $css);
	    setCSS('jquery.ui.tabs.css', $css);
	    setCSS('jquery.ui.datepicker.css', $css);
	}	
					
	$res = new resClass;
			
	$sql = "SELECT * FROM `" . $dbTables['menu_types'] . "` WHERE `menutype` = ? AND `lang` = ? LIMIT 1";
	$params = array (
	    'menutype' => $_SESSION['mt'], 
	    'lang' => $lang
	);
	$res->bind_execute( $params, $sql);
	$menuType = $res->data[0];	
				
	$pageTitle .= $menuType['name'];
		
	$crumbpath[] = array ('name' => $pageTitle, 'url' => $PHP_SELF . '?c=' . $_GET['c']);

	/**
	 * Zmiana pozycji
	*/	
	if ($_GET['action'] == 'setPos')
	{
		$i = 0;
		foreach($_POST as $k => $v) 
		{
			if ($k != 'change')
			{
				$tmp = explode('_',$k);
			
				$sql = "UPDATE `" . $dbTables['pages'] . "`  SET pos='".$v."' WHERE (`id` = ?) LIMIT 1";
			
				$params = array ('id' => $tmp[1] );							
							
				$res->bind_execute( $params, $sql);
				$numRows = $res->numRows;		
	
				if ( $numRows > 0)	
				{		
					$i++;
				}								
			}
		}
		if ($i > 0)
		{
			$message .= show_msg ('msg', 'Kolejność '.$i.' stron została zmieniona');
			monitor( $_SESSION['userData']['UID'], 'Zmiana kolejności '.$i.' stron' , get_ip() );
		}
	}	
						
	/**
	 * Dodanie 
	*/
	if ($_GET['action'] == 'add')
	{
	    $crumbpath[] = array ('name' => 'Dodaj zakładkę', 'url' => $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=' . $_GET['action']);
			
	    $pageTitle .= ' - Dodaj zakładkę';
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

		    $sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables['pages'] . "` WHERE (ref = ?) AND (menutype = ?) AND (lang = ?)";
		    $params = array (
				'ref' => $_POST['ref'], 
				'menutype' => $_SESSION['mt'], 
				'lang' => $lang
		    );				
		    $res->bind_execute( $params, $sql);
		    $maxPos = $res->data[0]['maxPos'] + 1;
					
			$sql = "INSERT INTO `" . $dbTables['pages'] . "` VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?, ?, 'dynamic', ?, ?, ?, ?, ?, ?, '0')";

			$params = array (
						'menutype' => $_SESSION['mt'], 
						'ref' => $_POST['gr'], 
						'pos' => $maxPos, 
						'name' => strip_tags($_POST['name']),
						'url_name' => strip_tags(trans_url_name($_POST['url_name'])),
						'lead_text' => $_POST['lead_text'], 
						'text' => $_POST['text'],
						'author' => strip_tags($_POST['autor']),
						'wprowadzil' => strip_tags($_POST['wprowadzil']),
						'podmiot' => strip_tags($_POST['podmiot']),
						'attrib' => serialize($_POST['attrib']), 
						'ext_url' => $_POST['ext_url'],
						'new_window' => $_POST['new_window'],
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
		    $idPage = $res->lastID; 	

					
		    if ( $numRows > 0)	
		    {		
				$message .= show_msg ('msg', $MSG_add);
				monitor( $_SESSION['userData']['UID'], $MON_page_add . $_POST['name'] , get_ip() );
				
				add_to_register ($idPage, $_SESSION['type_to_files']);
							
				// jesli uzytkownik nie jest adminem to aktualizacja uprawnien
				if ($_SESSION['userData']['type'] != 'admin') 
				{
					$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='pages') AND (`id_user`= ?) LIMIT 1";
					$params = array ('id_user' =>  $_SESSION['userData']['UID']	);
					$res->bind_execute( $params, $sql);
					$id_rec = $res->data[0]['id_rec'];
					$id_rec .= $idPage.',';
					
					$sql = "UPDATE `" . $dbTables['priv'] . "` SET id_rec = ? WHERE (id_tbl = 'pages')  AND (`id_user`= ?) LIMIT 1";
					$params = array (
					'id_rec' => $id_rec, 
					'id_user' =>  $_SESSION['userData']['UID']	
					);
					$res->bind_execute( $params, $sql);
					$outPagesPriv = explode(',', $id_rec);	
					$_SESSION['userData']['privPages'] = $outPagesPriv;
				}	
							
				if ($_POST['save'])
				{
					$showAddForm = false;
					$showList = true;
				}				
		    }							
		}
	    }
	    
	    // pobranie drzewka grup
	    $sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`lang` = ?) ORDER BY pos";
	    $params = array (
			    'menutype' => $_SESSION['mt'],
			    'lang' => $lang
			    );
	    $res->bind_execute( $params, $sql);
	    $menuTree = $res->data;	    

	    if ($_POST['saveAdd'] || !$_POST['save'])
	    {
			$showAddForm = true;
	    }
	}
	
	// sprawdzenie czy sa uprawnienia do tej strony			
	if ( get_priv_pages($_GET['id']) ) 
	{	
	    /**
	     * Edycja
	    */
	    if ($_GET['action'] == 'edit')
	    {
			$pageTitle .= ' - Edytuj zakładkę';
					
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
					//debug($_POST['gr']);
					if ($_POST['gr'] >= 0 && $_POST['gr'] != $_GET['id'])
					{
						$add_sql = "ref = '".$_POST['gr']."', ";
					}
					
					$sql = "UPDATE `" . $dbTables['pages'] . "` SET " . $add_sql . "
							name = ?, url_name = ?, lead_text = ?, text = ?, author = ?, wprowadzil = ?, podmiot = ?, attrib = ?, ext_url = ?, new_window = ?, protected = ?, ingallery = ?,
							modified_date = ?, show_date = ?, start_date = ?, stop_date = ? WHERE (`id` = ?) LIMIT 1";
	
					$params = array (
								'name' => strip_tags($_POST['name']),
								'url_name' => strip_tags(trans_url_name($_POST['url_name'])),
								'lead_text' => $_POST['lead_text'], 
								'text' => $_POST['text'],
								'author' => strip_tags($_POST['autor']),
								'wprowadzil' => strip_tags($_POST['wprowadzil']),
								'podmiot' => strip_tags($_POST['podmiot']),
								'attrib' => serialize($_POST['attrib']), 
								'ext_url' => $_POST['ext_url'],
								'new_window' => $_POST['new_window'],
								'protected' => $_POST['protected'],
								'ingallery' => $_POST['ingallery'],
								'modified_date' => $modified_date,
								'show_date' => $_POST['show_date'],
								'start_date' => $_POST['start_date'],
								'stop_date' => $_POST['stop_date'],
								'id' => $_GET['id'] 
							);							
								
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;		
		
					if ( $numRows > 0)	
					{		
						$message .= show_msg ('msg', $MSG_edit);
						monitor( $_SESSION['userData']['UID'], $MON_page_edit . $_POST['name'] , get_ip() );
		
						add_to_register ($_GET['id'], $_SESSION['type_to_files']);		

						if ($_POST['saveEdit']) {
							$showEditForm = false;
							$showList = true;
						}						
					}				
				}
			}
					
			$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`id`= ?) LIMIT 1";
			$params = array ('id' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];	
			$pageName = $row['name'];
			$menuType = $row['menutype'];
	
			$attrib = unserialize($row['attrib']);	
			$row['show_date'] = substr($row['show_date'], 0, 10);
			$row['start_date'] = substr($row['start_date'], 0, 10);
			$row['stop_date'] = substr($row['stop_date'], 0, 10);	
															
			// podglad strony
			if ($_POST['view'])
			{				
				$row['name'] = stripslashes($_POST['name']);
				$row['text'] = stripslashes($_POST['text']);
				$row['ext_url'] = $_POST['ext_url'];
				$row['new_window'] = $_POST['new_window'];
				$attrib['art_num'] = $_POST['attrib']['art_num'];
				$attrib['meta_title'] =  stripslashes($_POST['attrib']['meta_title']);
				$attrib['meta_desciption'] =  stripslashes($_POST['attrib']['meta_desciption']);
				$attrib['meta_keywords'] =  stripslashes($_POST['attrib']['meta_keywords']);
				$row['ingallery'] = $_POST['ingallery'];
				$row['protected'] = $_POST['protected'];
				$row['author'] =  stripslashes($_POST['autor']);
				$row['wprowadzil'] = stripslashes($_POST['wprowadzil']);
				$row['show_date'] = $_POST['show_date'];
				$row['start_date'] = $_POST['start_date'];
				$row['stop_date'] = $_POST['stop_date'];
	
				$text['name'] = strtr($row['name'], $cmsConfig['replace_char_toview']);
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
					
			$crumbpath[] = array ('name' => $pageName, 'url' => '');
			$crumbpath[] = array ('name' => 'Edytuj zakładkę', 'url' => $PHP_SELF . '?c=' . $_GET['c'] . '&amp;id=' . $_GET['id'].'&amp;action=' . $_GET['action']);	
				
			// pobranie drzewka grup
			$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`lang` = ?) ORDER BY pos";
			$params = array (
					'menutype' => $_SESSION['mt'],
					'lang' => $lang
					);
			$res->bind_execute( $params, $sql);
			$menuTree = $res->data;	   				
	    }
			
	    /**
	     * Usuwanie grupy
	     */

			function delete_pages( $numPages, $id ) 
			{			
				global $dbTables, $lang;

				$res = new resClass;
				
				include ( CMS_BASE . DS . 'includes' . DS . $lang . DS . 'messages.php');
								
				// wybranie grupy
				$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`ref` = ?) LIMIT 1 ";
				$params = array ('id' => $id);
				$res->bind_execute( $params, $sql);
				$rowPages = $res->data;
				
				foreach ($rowPages as $row)
				{
					$pageName = $row['name'];
					$pageID = $row['id'];						
					
					// usuniecie grupy
					$sql = "DELETE FROM `" . $dbTables['pages'] . "` WHERE (`id` = ?) LIMIT 1";
					$params = array ('id' => $pageID);
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;	
			
					if ( $numRows > 0)		
					{
						// jesli uzytkownik nie jest adminem to aktualizacja uprawnien
						if ($_SESSION['userData']['type'] != 'admin') 
						{
							$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='pages') AND (`id_user`= ?) LIMIT 1";
							$params = array ('id_user' =>  $_SESSION['userData']['UID']	);
							$res->bind_execute( $params, $sql);
							$id_rec = explode(',', $res->data[0]['id_rec']);
							foreach( $id_rec as $k => $v ) {
								if( $pageID == $v ) {
									unset( $id_rec[ $k ] );
								} 
							} 
							$id_rec = implode(',', $id_rec);
							
							$sql = "UPDATE `" . $dbTables['priv'] . "` SET id_rec = ? WHERE (id_tbl = 'pages')  AND (`id_user`= ?) LIMIT 1";
							$params = array (
									'id_rec' => $id_rec, 
									'id_user' =>  $_SESSION['userData']['UID']	
							);
							$res->bind_execute( $params, $sql);
							$outPagesPriv = explode(',', $id_rec);	
							$_SESSION['userData']['privPages'] = $outPagesPriv;
						}	
										
						$message .= show_msg ('msg', $MSG_del);
						monitor( $_SESSION['userData']['UID'], $MON_page_del . $pageName , get_ip() );	
						
						// zmiana pozycji pozostałych grup
						$sql = "UPDATE `" . $dbTables['pages'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (ref = ?) AND (menutype = ?) AND (lang = ?)";
						$params = array (
									'pos' => $row['pos'], 
									'ref' => $row['ref'], 
									'menutype' =>$row['menutype'], 
									'lang' => $lang
									);
						$res->bind_execute( $params, $sql);
				
						/**
						 * Usuwanie powiazanych articles
						 *  - Usuwanie powiazanych zdjec z articles
						 *  - Usuwanie powiazanych plikow z articles
						 */	
		
						$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
								FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
								ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
								WHERE (`" . $dbTables['art_to_pages'] . "`.id_page = ?)";	
								
						$params = array( 'id_page'=> $pageID );
						$res->bind_execute( $params, $sql);
						
						foreach ($res->data as $row)
						{
							$sql = "DELETE FROM `" . $dbTables['articles'] . "` WHERE (`id_art` = ?) LIMIT 1";
							$params = array ('id_art' => $row['id_art']);
							$res->bind_execute( $params, $sql);
						
								/**
								 * Usuwanie powiazanych z articles zdjec
								 */			
								$params = array( 
									'id_page'=> $row['id_art'],
									'type' => 'article'  
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
								/*
								if ($numRowsPhotos > 0) {
									$message .= show_msg ('msg', $MSG_del_photos . $numRowsPhotos);
								}
								*/					
													
								/**
								 * Usuwanie powiazanych z articles plikow do pobrania
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
								/*
								if ($numRowsFiles > 0) {
									$message .= show_msg ('msg', $MSG_del_files . $numRowsFiles);				 
								}
								*/
								
								/**
								 * Usuwanie wpisow w rejestrze
								 */	
								$sql = "DELETE FROM `" . $dbTables['register'] . "` WHERE (`idg`= ?)";
								$params = array ('idg' => $row['id_art']);
								$res->bind_execute( $params, $sql);										
						}		
						
						// usuniecie articles z powiazanych
						$sql = "DELETE FROM `" . $dbTables['art_to_pages'] . "` WHERE (`id_page` = ?) ";
						$params = array ('id_page' => $pageID);
						$res->bind_execute( $params, $sql);
						$numRowsDelArt = $res->numRows;	
						
						if ($numRowsDelArt > 0) 
						{
							monitor( $_SESSION['userData']['UID'], $pageName . ' - ' . $MON_articles_del . ' ' . $numRowsDelArt , get_ip() );	
							$message .= show_msg ('msg', $MSG_del_articles . $numRowsDelArt);	
						}
						
										
						/**
						 * Usuwanie powiazanych z grupami zdjec
						 */			
						$params = array( 
							'id_page'=> $pageID,
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
						 * Usuwanie powiazanych z grupami plikow do pobrania
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
						$sql = "DELETE FROM `" . $dbTables['register'] . "` WHERE (`idp`= ?)";
						$params = array ('idp' => $pageID);
						$res->bind_execute( $params, $sql);								
							
					}	
					
					$numPages--;							
					if($numPages > 0)
					{
						delete_pages( $numPages, $pageID );				
					}
				}
			}
			
			
			if ($_GET['action'] == 'delete')
			{
				$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`lang` = ?)";
				$params = array (
						'menutype' => $_SESSION['mt'],
						'lang' => $lang
						);
				$res->bind_execute( $params, $sql);
				$numPages = $res->numRows;
			
				// wybranie grupy
				$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`id` = ?) LIMIT 1 ";
				$params = array ('id' => $_GET['id']);
				$res->bind_execute( $params, $sql);
				$row = $res->data[0];	
				$pageName = $row['name'];
				
				
				// usuniecie zagniedzonych grup
				delete_pages( $numPages, $_GET['id'] );	
				
				// usuniecie grupy
				$sql = "DELETE FROM `" . $dbTables['pages'] . "` WHERE (`id` = ?) LIMIT 1";
				$params = array ('id' => $_GET['id']);
				$res->bind_execute( $params, $sql);
				$numRows = $res->numRows;	
		
				if ( $numRows > 0)		
				{		
					// jesli uzytkownik nie jest adminem to aktualizacja uprawnien
					if ($_SESSION['userData']['type'] != 'admin') 
					{
						$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='pages') AND (`id_user`= ?) LIMIT 1";
						$params = array ('id_user' =>  $_SESSION['userData']['UID']	);
						$res->bind_execute( $params, $sql);
						$id_rec = explode(',', $res->data[0]['id_rec']);
						foreach( $id_rec as $k => $v ) {
							if( $_GET['id'] == $v ) {
								unset( $id_rec[ $k ] );
							} 
						} 
						$id_rec = implode(',', $id_rec);
						
						$sql = "UPDATE `" . $dbTables['priv'] . "` SET id_rec = ? WHERE (id_tbl = 'pages')  AND (`id_user`= ?) LIMIT 1";
						$params = array (
								'id_rec' => $id_rec, 
								'id_user' =>  $_SESSION['userData']['UID']	
						);
						$res->bind_execute( $params, $sql);
						$outPagesPriv = explode(',', $id_rec);	
						$_SESSION['userData']['privPages'] = $outPagesPriv;
					}	
									
					$message .= show_msg ('msg', $MSG_del);
					monitor( $_SESSION['userData']['UID'], $MON_page_del . $pageName , get_ip() );	
					
					// zmiana pozycji pozostałych grup
					$sql = "UPDATE `" . $dbTables['pages'] . "` SET pos=pos-1 WHERE (pos >= ?)  AND (ref = ?) AND (menutype = ?) AND (lang = ?)";
					$params = array (
								'pos' => $row['pos'], 
								'ref' => $row['ref'], 
								'menutype' =>$row['menutype'], 
								'lang' => $lang
								);
					$res->bind_execute( $params, $sql);
			
					/**
					 * Usuwanie powiazanych articles
					 *  - Usuwanie powiazanych zdjec z articles
					 *  - Usuwanie powiazanych plikow z articles
					 */	
	
					$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
							FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
							ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
							WHERE (`" . $dbTables['art_to_pages'] . "`.id_page = ?)";	
							
					$params = array( 'id_page'=> $_GET['id']);
					$res->bind_execute( $params, $sql);
					
					foreach ($res->data as $row)
					{
						$sql = "DELETE FROM `" . $dbTables['articles'] . "` WHERE (`id_art` = ?) LIMIT 1";
						$params = array ('id_art' => $row['id_art']);
						$res->bind_execute( $params, $sql);
					
							/**
							 * Usuwanie powiazanych z articles zdjec
							 */			
							$params = array( 
								'id_page'=> $row['id_art'],
								'type' => 'article'  
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
							/*
							if ($numRowsPhotos > 0) {
								$message .= show_msg ('msg', $MSG_del_photos . $numRowsPhotos);
							}
							*/					
												
							/**
							 * Usuwanie powiazanych z articles plikow do pobrania
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
							/*
							if ($numRowsFiles > 0) {
								$message .= show_msg ('msg', $MSG_del_files . $numRowsFiles);				 
							}
							*/

							/**
							 * Usuwanie wpisow w rejestrze
							 */	
							$sql = "DELETE FROM `" . $dbTables['register'] . "` WHERE (`idg`= ?)";
							$params = array ('idg' => $row['id_art']);
							$res->bind_execute( $params, $sql);									
							
					}		
					
					// usuniecie articles z powiazanych
					$sql = "DELETE FROM `" . $dbTables['art_to_pages'] . "` WHERE (`id_page` = ?) ";
					$params = array ('id_page' => $_GET['id']);
					$res->bind_execute( $params, $sql);
					$numRowsDelArt = $res->numRows;	
					
					if ($numRowsDelArt > 0) 
					{
						monitor( $_SESSION['userData']['UID'], $pageName . ' - ' . $MON_articles_del . ' ' . $numRowsDelArt , get_ip() );	
						$message .= show_msg ('msg', $MSG_del_articles . $numRowsDelArt);	
					}
					
									
					/**
					 * Usuwanie powiazanych z grupami zdjec
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
					 * Usuwanie powiazanych z grupami plikow do pobrania
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
					$sql = "DELETE FROM `" . $dbTables['register'] . "` WHERE (`idp`= ?)";
					$params = array ('idp' => $_GET['id']);
					$res->bind_execute( $params, $sql);							
				}	
			
			}
								
			/**
			 * De-Aktywacja
			 */
			if ($_GET['action'] == 'noactive' &&  get_priv_pages($_GET['id']))
			{
				$sql = "UPDATE `" . $dbTables['pages'] . "` SET active = ? WHERE (`id` = ?) LIMIT 1";
				$params = array (
								'active' => 0, 
								'id' => $_GET['id']
								);
				$res->bind_execute( $params, $sql);
				
				$sql = "UPDATE `" . $dbTables['pages'] . "` SET active = ? WHERE (`ref` = ?)";
				$params = array (
								'active' => 0, 
								'ref' => $_GET['id']
								);
				$res->bind_execute( $params, $sql);
						
			}
			
			/**
			 * Aktywacja
			 */
			if ($_GET['action'] == 'active' &&  get_priv_pages($_GET['id']))
			{
				$sql = "UPDATE `" . $dbTables['pages'] . "` SET active = ? WHERE (`id` = ?) LIMIT 1";
				$params = array (
								'active' => 1, 
								'id' => $_GET['id']
								);
				$res->bind_execute( $params, $sql);
				
				$sql = "UPDATE `" . $dbTables['pages'] . "` SET active = ? WHERE (`ref` = ?)";
				$params = array (
								'active' => 1, 
								'ref' => $_GET['id']
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
							monitor( $_SESSION['userData']['UID'], 'Strona - Zdjęcie ' .  $_FILES['file'.$i]['name'] . ' - zostało skopiowane.' , get_ip() );
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
			$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
			$message .= show_msg ('err', $ERR_priv_access);				
		}				
						
		/**
		 * Pobranie drzewka
		 */
		$menuArr = array();

		function get_panel_menu_tree ($ref = 0, $numline = 0)
		{
			global $dbTables, $lang, $MSG_del_confirm, $menuArr, $shortDate;
			
			// max ilosc zagniezdzen
			if ( $_SESSION['mt'] == 'tm') {
				$nestNum = 2;
			}
			if ( $_SESSION['mt'] == 'mg') {
				$nestNum = 3;
			}
			
			$res = new resClass;
	
			$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`ref` = ?) AND (`lang` = ?) ORDER BY pos";
			$params = array (
					'menutype' => $_SESSION['mt'],
					'ref' => $ref, 
					'lang' => $lang
					);
			$res->bind_execute( $params, $sql);
			$numPages = $res->numRows;
			
			if ($numPages > 0)			
			{
				echo '<ul class="connectedSortable">';
				$n = 0;
				foreach ($res->data as $row)
				{
					$n++;
					if ( get_priv_pages($row['id']) ) 
					{
						$rowColor = '';
						
						$res2 = new resClass;
						$sql = "SELECT COUNT(id) AS numRef FROM `" . $dbTables['pages'] . "` WHERE (`ref` = ?) AND (`lang` = ?)";
						$params = array (
								'ref' => $row['id'], 
								'lang' => $lang
								);
						$res2->bind_execute( $params, $sql);				
						$numRef = $res2->data[0]['numRef'];
						
						$numline++;	
						$active = '';
						if ($row['active'] == 1) {
							$active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=noactive&amp;id=' . $row['id'] . '" title="Ukryj"><img src="template/images/icoStat1.png" alt="Ukryj" class="imgAct" /></a> ';
						}
						else {
							$active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=active&amp;id=' . $row['id'] . '" title="Pokaż"><img src="template/images/icoStat0.png" alt="Pokaz" class="imgAct" /></a> ';
							$active = ' class="noActive"';
						}
						$liClass = 'menTLi';
						$num = $res->numRows;
						if ($num == $n){
							$liClass = ' menTLiLast';
						}
						
						if ( $numline == $nestNum){
							$liClass .= ' no-nest';
						}
						
						$row['start_date'] = substr($row['start_date'], 0, 10);
						$row['stop_date'] = substr($row['stop_date'], 0, 10);
						
						if ($row['start_date']!='0000-00-00' && $row['stop_date']!='0000-00-00')
							$odDo = $row['start_date'].'<br/>'.$row['stop_date'];
						else
							$odDo = 'bez przerwy';
							
						if (!($row['start_date']<=$shortDate && $row['stop_date']>=$shortDate) && ($row['start_date']!='0000-00-00' && $row['stop_date']!='0000-00-00') || $row['active']==0) 
						{
							$rowColor = ' noactive';
						}
											
						echo '<li id="grId_' . $row['id'] . '" class="'. $liClass . $rowColor .'">';
						
						if ($numRef > 0)
							$spacja = '';
						else
							$spacja = '';
		
						$w = ($numline -1) * 50;
						$spacja .= '' ;
							
						$menuArr['id'][] = $row['id'];
						$menuArr['name'][] = $row['name'];
						$menuArr['numline'][] = $numline;

						echo '<div class="menuTreeLi">'
								. $spacja . '<span class="menuTreeIco"></span><span class="menuTreeName">' . $row['name'] . '</span>'
								.'<div class="menuTreeCells">'
									.'<div class="menuTreeShow">'.$active_url.'</div>'
									.'<div class="menuTreePos"><label for="id_'.$row['id'].'" class="hide">Ustal kolejność dla ' . $row['name'] . '</label>'
										.'<select name="pos_'.$row['id'].'" id="id_'.$row['id'].'" class="selPos">';
										for ($j=1; $j<=$numPages; $j++)
										{
											if ($row['pos'] == $j)
												echo '<option selected="selected">'.$j.'</option>';
											else
												echo '<option>'.$j.'</option>';
										} 
										echo '</select>';

								echo '</div>'
									.'<div class="menuTreeAction">'
										.'<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=edit&amp;id=' . $row['id'] . '" title="Edytuj pozycję"><img src="template/images/icoEdit.png" alt="Edytuj pozycję" class="imgAct" /></a> ';
								
										echo '<a href="'.$PHP_SELF.'?c=articles&amp;idp=' . $row['id'] . '&amp;mt='.$_SESSION['mt'].'" title="Artykuły"><img src="template/images/icoArticles.png" alt="Artykuły" class="imgAct" /></a> ';
										echo '<a href="'.$PHP_SELF.'?c=register&amp;idp=' . $row['id'] . '&amp;mt='.$_SESSION['mt'].'" title="Zobacz rejestr zmian"><img src="template/images/icoRegister.png" alt="Zobacz rejestr zmian" class="imgAct" /></a> ';
										if ($row['type'] == 'dynamic') 
										{
											echo '<a href="javascript:confirmLink(\'' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=delete&amp;id=' . $row['id'] . '\',\'' . $MSG_del_confirm . '\');" title="Usuń pozycję" class="delLink"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a> ';
										}						
								echo '</div>'
						    .'</div>'
						    .'</div>';							
						get_panel_menu_tree ($row['id'], $numline);
						$numline--;	
						echo '</li>';
					}
					
				}
				echo '</ul>';
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
