<?php
if ($showPanel)
{
    /**
     * Jesli uzytkownik nie jest adminem nie ma dostepu
     */	
    if ($_SESSION['userData']['type'] != 'admin') 
    {
		header('Location: '.url_redirect ('index.php'));
    } 
	else
    {
		$pageTitle = $pageTitle ?? '';
		$_GET['action'] = $_GET['action'] ?? '';
		
		$TEMPL_PATH = CMS_TEMPL . DS . 'privileges.php';
	
		$res = new resClass;
					
		$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`id_user`= ?) LIMIT 1";
		$params = array ('id' => $_GET['id']);
		$res->bind_execute( $params, $sql);
		$row = $res->data[0];	
			
		$pageTitle .= $TXT_menu_priv . ' dla ' .$row['name'];
			
		$crumbpath[] = array ('name' => $TXT_menu_users, 'url' => $PHP_SELF . '?c=users');
		$crumbpath[] = array ('name' => $TXT_menu_priv , 'url' => $PHP_SELF . '?c=' . $_GET['c'].'&id=' . $row['id_user']);
		$crumbpath[] = array ('name' => $row['name'] , 'url' => $PHP_SELF . '?c=' . $_GET['c'].'&id=' . $row['id_user']);
			
		/**
		* Aktualizacja uprawnien
		*/				
		if ($_GET['action'] == 'update')
		{
			$sql = "DELETE FROM `" . $dbTables['priv'] . "` WHERE (`id_user`= ?)";
			$params = array ('id' => $_GET['id']);
			$res->bind_execute( $params, $sql);
	
			// grupy i podgrupy
			$recordsPage = '';
			foreach($_POST as $k => $v) 
			{
				if (substr($k,0,3)=='pg_')
				{
					$recordsPage .= $v . ',';
				}
			}
			$sql = "INSERT INTO `" . $dbTables['priv'] . "` VALUES ('', ?, 'pages', ?)";
			$params = array (
				'id_user' => $row['id_user'], 
				'id_rec' => $recordsPage,
			);							
			$res->bind_execute( $params, $sql);
				
			// menu panelu 
			$recordsMenu = '';
			foreach($_POST as $k => $v) 
			{
				if (substr($k,0,3)=='mp_')
				{
					$recordsMenu .= $v . ',';
				}
			}
			$sql = "INSERT INTO `" . $dbTables['priv'] . "` VALUES ('', ?, 'menu_panel', ?)";
			$params = array (
				'id_user' => $row['id_user'], 
				'id_rec' => $recordsMenu,
			);							
			$res->bind_execute( $params, $sql);
	
			// menu dynamiczne 
			$recordsMenuDyn = '';
			foreach($_POST as $k => $v) 
			{
				if (substr($k,0,4)=='mpd_')
				{
					$recordsMenuDyn .= $v . ',';
				}
			}
			$sql = "INSERT INTO `" . $dbTables['priv'] . "` VALUES ('', ?, 'menu_dyn', ?)";
			$params = array (
				'id_user' => $row['id_user'], 
				'id_rec' => $recordsMenuDyn,
			);							
			$res->bind_execute( $params, $sql);
			
				
			$message .= show_msg ('msg', $MSG_user_priv_edit);
			monitor( $_SESSION['userData']['UID'], $MON_user_priv_edit . $row['name'] , get_ip() );
		}
			
		$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='menu_panel') AND (`id_user`= ?) LIMIT 1";
		$params = array ('id' => $_GET['id']);
		$res->bind_execute( $params, $sql);
		$mp_idrec = [];
		if (($res->numRows != 0)) {
			$row = $res->data[0];				
			$mp_idrec = explode(',',$row['id_rec']);
			sort($mp_idrec);
		}
	
		$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='menu_dyn') AND (`id_user`= ?) LIMIT 1";
		$params = array ('id' => $_GET['id']);
		$res->bind_execute( $params, $sql);
		$mpd_idrec = [];
		if (($res->numRows != 0)) {
			$row = $res->data[0];				
			$mpd_idrec = explode(',',$row['id_rec']);
			sort($mpd_idrec);
		}
			
		$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='pages') AND (`id_user`= ?) LIMIT 1";
		$params = array ('id' => $_GET['id']);
		$res->bind_execute( $params, $sql);
		$page_idrec = [];
		if (($res->numRows != 0)) {
			$row = $res->data[0];				
			$page_idrec = explode(',',$row['id_rec']);
			sort($page_idrec);
		}
			
		// funkcja rekurencyjna wyswietlajaca checkboxy do uprawnien uzytkowników
		function show_priv_checkbox($count, $mt, $ref, $page_idrec)
		{
			global $dbTables, $lang;
				
			$res = new resClass;
			$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`ref` = ?) AND (`lang` = ?) ORDER BY pos";
			$params = array (
				'menutype' => $mt,
				'ref' => $ref, 
				'lang' => $lang
			);
			$res->bind_execute( $params, $sql);			
			if ($res->numRows > 0)			
			{			
				echo '<ul class="pagePriv">';
				foreach ($res->data as $row)
				{			
					$checked = ''; 
					for ($j=1; $j<=count($page_idrec); $j++)
					{ 	 
						if (isset($page_idrec[$j]) && ($row['id'] == $page_idrec[$j]))
							$checked = 'checked="checked"';
					}	
			
					echo '<li>'
						.'<input type="checkbox" name="pg_'.$row['id'].'" id="pg_'.$row['id'].'" value="'.$row['id'].'" '.$checked.'/> '
						.'<label for="pg_'.$row['id'].'" class="checkInput">'.$row['name'].'</label>';
							
					$count--;
				
					if($count>0) 
					{
						show_priv_checkbox($count, $mt, $row['id'], $page_idrec);	
					}
					echo '</li>';		
				}
				echo '</ul>';
			}
		}							
    }
}
?>