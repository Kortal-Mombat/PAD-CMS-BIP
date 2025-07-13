<?php
if ($_COOKIE["login_timeout"] > 0)
{
	$TEMPL_PATH = CMS_TEMPL . DS . 'login_timeout.php';
}
else
{
	$TEMPL_PATH = CMS_TEMPL . DS . 'login.php';
	$res = new resClass;
		
	/**
	 * Logowanie
	 */	
	if (isset($_GET['action']) && $_GET['action'] == 'login') 
	{
		$f_user = $_POST['form_user'];
		$f_pass = sha1($_POST['form_pass'].$salt);		
	
		$res = new resClass;
			
		$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`login` = ? ) AND (`passwd` = ? ) LIMIT 1";
		$params = array(
		'login' => $f_user,
		'pass' => $f_pass
		);
			
		$res->bind_execute( $params, $sql);
		
		$numRows = $res->numRows;	
							
		if ($numRows > 0)
		{
			$r = $res->data[0];	
			if ($r['active'] != 1)
			{
				$message .= show_msg ('err', 'Twoje konto jest zablokowane. Skontakuj się z administratorem serwisu.');
			} 
			else
			{
				// pobranie uprawnien do lewego menu
				$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='menu_panel') AND (`id_user`= ?) LIMIT 1";
				$params = array ('id' => $r['id_user']);
				$res->bind_execute( $params, $sql);
				$id_rec = $res->data[0]['id_rec'];
				$outMenuPriv = explode(',', $id_rec);	
		
				// na podstawie uprawnien do lewego menu wyluskanie kontrolerow
				$sql = "SELECT id_mp, controler, link FROM `" . $dbTables['menu_panel'] . "` WHERE (id_mp IN (".substr($id_rec,0,-1).")) ORDER BY pos";
				$params = array ();
				$res->bind_execute( $params, $sql);
				foreach ($res->data as $k)
				{
					$r['privControler'][] = $k['controler'];
				}
						
				// pobranie uprawnien do grup					
				$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='pages') AND (`id_user`= ?) LIMIT 1";
				$params = array ('id' => $r['id_user']);
				$res->bind_execute( $params, $sql);
				$outPagesPriv = explode(',', $res->data[0]['id_rec']);	
				
				// pobranie uprawnien do menu dyn					
				$sql = "SELECT * FROM `" . $dbTables['priv'] . "` WHERE (id_tbl='menu_dyn') AND (`id_user`= ?) LIMIT 1";
				$params = array ('id' => $r['id_user']);
				$res->bind_execute( $params, $sql);
				$outMenuDynPriv = explode(',', $res->data[0]['id_rec']);	
									
				$r['privMenu'] = $outMenuPriv;
				$r['privPages'] = $outPagesPriv;
				$r['privMenuDyn'] = $outMenuDynPriv;			
					
				//debug($r);	
	
				set_user_session($r);
				
				// aktualny numer sesji
				$sessionInfo = '==' .  $_SESSION['userData']['SID'];			
				
				monitor( $r['id_user'], $MON_login . $sessionInfo, get_ip() );	
				
				setcookie ("login_count", "", time() - 3600);
				setcookie ("login_timeout", "", time() - 3600);			
			}
		} 
		else
		{
			monitor( $r['id_user'], $MON_err_login . ' (' . $TXT_login . ':' . $f_user . ')', get_ip() );	
			$message .= show_msg ('err', $ERR_login);
			
			$c = $_COOKIE["login_count"]+1;
			setcookie("login_count", $c);
			
			if ($c >= 6)
			{
				setcookie("login_timeout", '5', time() + 300); //5 min
			}
		}			
			
		if ($_SESSION['userData'] != 'empty')
		{
			header('Location: '.url_redirect ('index.php'));
			exit;
		}
	}

	
	if ($res->error != '')	
	{
		$message .= show_msg ('err', $ERR_004 . '['.$res->error.']');	
	}	
}
?>