<?php
if ($showPanel)
{
	if ( get_priv_controler('page', $_SESSION['mt']) && get_priv_pages($_GET['idp']) ) 
	{	
		$TEMPL_PATH = CMS_TEMPL . DS . 'register.php';
		$pageTitle .= $TXT_menu_register;
		
		$res = new resClass;
		$showList = true;
		
		// Dla articles
		if ($_GET['id'])
		{
			$crumbpath[] = array ('name' => $TXT_menu_register, 'url' => $PHP_SELF . '?c=' . $_GET['c'].'&amp;id=' . $_GET['id'] . '&amp;idp=' . $_GET['idp']);
			
			$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
					FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
					ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
					WHERE (`" . $dbTables['articles'] . "`.id_art = ?)
					LIMIT 1";
			$params = array ('id_art' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			
			$idArt = $res->data[0]['id_art'];
			
			$selectColumn = 'idg';
			
			$backLink = $PHP_SELF.'?c=articles&amp;idp=' . $_GET['idp'];
			$delLink = $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=del&amp;id=' . $_GET['id'] . '&amp;idp=' . $_GET['idp'];
		}
		// Dla pages
		else
		{
			$crumbpath[] = array ('name' => $TXT_menu_register, 'url' => $PHP_SELF . '?c=' . $_GET['c'].'&amp;idp=' . $_GET['idp']);
			
			$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`id`= ?) LIMIT 1";
			$params = array ('id' => $_GET['idp']);
			$res->bind_execute( $params, $sql);
			
			$idArt = $res->data[0]['id'];
			
			$selectColumn = 'idp';
			
			$backLink = $PHP_SELF.'?c=page&amp;mt=' . $res->data[0]['menutype'];
			$delLink = $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=del&amp;idp=' . $_GET['idp'];
		}
		
		$articleName = $res->data[0]['name'];	
		if (!$articleName) {
			$articleName = 'Brak nazwy artykulu (ID: ' . $idArt . ' )';
		}
	
		/**
		 * Poprzednia wersja
		 */
		if ($_GET['action'] == 'show')
		{
			$showList = false;
			$backLink = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;mt=' . $_SESSION['mt'] . '&amp;idp=' . $_GET['idp'] . '&amp;id='.$_GET['id'];
			
			$sql = "SELECT * FROM `" . $dbTables['register'] . "` WHERE (`id`= ?) LIMIT 1";
			$params = array( 'id' => $_GET['idr']);
			$res->bind_execute( $params, $sql);
			$outRow = $res->data;	
			$articleName .= ' - ' .$outRow[0]['akcja'];	
			$articleText = $outRow[0]['old_text'];
		}
						
		/**
		 * Usuwanie wpisu z rejestru
		 */
		if ($_GET['action'] == 'del')
		{
			$sql = "DELETE FROM `" . $dbTables['register'] . "` WHERE (`id`= ?)";
			$params = array ('id' => $_GET['idr']);
			$res->bind_execute( $params, $sql);
			$numRows = $res->numRows;	
	
			if ( $numRows > 0)	
			{		
				$message .= show_msg ('msg', $MSG_del);
				monitor( $_SESSION['userData']['UID'], $MON_register_del . $articleName , get_ip() );
			}
		}
		
		/**
		 * pokazanie wpisow 
		 */	
		if ($showList)
		{
			// pobranie wpisow z rejestru zmian
			$sql = "SELECT COUNT(id) AS total_records FROM `" . $dbTables['register'] . "` WHERE (`".$selectColumn."`= ?)";
			$params = array( $selectColumn => $idArt);
			$res->bind_execute( $params, $sql);
			$r = $res->data[0];	
			$numRows = $r['total_records'];		
			
			if ($numRows > 0)
			{
				$outRow = array();
				
				$sql = "SELECT * FROM `" . $dbTables['register'] . "` WHERE (`".$selectColumn."`= ?) ORDER BY `id` DESC"; // LIMIT ".$sql_start.", ".$sql_limit
				$params = array( $selectColumn => $idArt);
				$res->bind_execute( $params, $sql);
				$outRow = $res->data;	
				
				$pagination = pagination ($r['total_records'], $cmsConfig['limit'], 2, $_GET['s']);
			}
			else
			{
				$message .= show_msg ('info', 'Brak zarejestrowanej aktywnosci.');	
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