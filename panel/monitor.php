<?php
if ($showPanel)
{
	if (get_priv_controler($_GET['c']))
	{	
		$TEMPL_PATH = CMS_TEMPL . DS . 'monitor.php';
		$pageTitle .= $TXT_menu_mon;
		
		$crumbpath[] = array ('name' => $TXT_menu_mon, 'url' => $PHP_SELF . '?c=' . $_GET['c']);
				
		$showMonitor = false;
		
		$res = new resClass;
		
		$sql_limit = 25;
 		
		/**
		 * Usuwanie monitora
		 */
		if ($_GET['action'] == 'del')
		{
			if (!$_GET['id'])
			{
				$userName = 'Nieautoryzowany';
				$_GET['id'] = 0;
			}
			else
			{
				// Pobranie nazwy uzytkownika
				$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`id_user`= ?)";
				$params = array( 'id_user' => $_GET['id']);
				$res->bind_execute( $params, $sql);
				
				$userName = $res->data[0]['name'];	
			}
			
			$sql = "DELETE FROM `" . $dbTables['monitor'] . "` WHERE (`id_user`= ?)";
			$params = array ('id' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$numRows = $res->numRows;	
	
			if ( $numRows > 0)	
			{		
				$message .= show_msg ('msg', $MSG_del);
				monitor( $_SESSION['userData']['UID'], $MON_monitor_del . $userName , get_ip() );
			}
		}
			
		/**
		 * Wypisanie wszystkich uzytkownikow
		 */	
		if ($_GET['action'] != 'show')
		{	 
			$sql = "SELECT * FROM `" . $dbTables['monitor'] . "` LEFT JOIN `" . $dbTables['users'] . "` ON `" . $dbTables['users'] . "`.id_user = `" . $dbTables['monitor'] . "`.id_user GROUP BY `" . $dbTables['monitor'] . "`.id_user";
			$params = array();
			$res->bind_execute( $params, $sql);
			$outRow = $res->data;	
			$numRows = $res->numRows;	
		}
		
		/**
		 * pokazanie wpisow dla konkretnego usera
		 */	
		if ($_GET['action'] == 'show')
		{
			$showMonitor = true;	
			
			// Pobranie nazwy uzytkownika
			$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`id_user`= ?)";
			$params = array( 'id_user' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			
			$userName = $res->data[0]['name'];	
			if (!$userName) {
				$userName = 'Nieautoryzowany';
			}
			
			// pobranie wpisow z monitora
			$sql = "SELECT COUNT(id_mon) AS total_records FROM `" . $dbTables['monitor'] . "` WHERE (`id_user`= ?)";
			$params = array( 'id_user' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			
			$r = $res->data[0];	
			$numRows = $r['total_records'];		
			
			if ($numRows > 0)
			{
				$outRow = array();
				
				$sql = "SELECT * FROM `" . $dbTables['monitor'] . "` WHERE (`id_user`= ?) ORDER BY `date` DESC LIMIT ".$sql_start.", ".$sql_limit;
				$params = array( 'id_user' => $_GET['id']);
				$res->bind_execute( $params, $sql);
					
				$outRow = $res->data;	
				
				$pagination = pagination ($r['total_records'], $cmsConfig['limit'], 2, $_GET['s']);
			}
			else
			{
				$message .= show_msg ('err', 'Brak zarejestrowanej aktywnosci.');	
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