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
		$_GET['act'] = $_GET['act'] ?? '';
		$_POST['action'] = $_POST['action'] ?? '';

		$TEMPL_PATH = CMS_TEMPL . DS . 'users.php';
		$pageTitle .= $TXT_menu_users;
		
		$crumbpath[] = array ('name' => $TXT_menu_users, 'url' => $PHP_SELF . '?c=' . $_GET['c']);
		
		$showList = true;
		$showAddForm = false;
		$showEditForm = false;	

		$res = new resClass;
				
		// lista akcji dla ktorych zaladowac dodatkowe js
		$actionJSList = array ('edit', 'add');
		if (in_array($_GET['action'],$actionJSList) )	
		{
			setJS('checkPasswd.js', $js);
		}		
		
		/**
		 * Dodanie uzytkownika
		 */
		if ($_GET['action'] == 'add')
		{
			$showAddForm = true;
			$showList = false;
			
			if ($_GET['act'] == 'addPoz')
			{
				if (!$_POST['name'] || !$_POST['login'] || !$_POST['passwd'] || !$_POST['passwd2'])
				{
					$message .= show_msg ('err', $ERR_all_fields);
				}
				else if ($_POST['passwd'] != $_POST['passwd2'])
				{
					$message .= show_msg ('err', $ERR_passwd_eq);
				}
				else if (strlen(trim($_POST['passwd'])) < $passLength)
				{
					$message .= show_msg ('err', $ERR_passwd_min .  $passLength);
				}
				else if (!check_email_addr($_POST['email']))
				{
					$message .= show_msg ('err', $ERR_email);
				} 				
				else
				{
					$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (login = ?) LIMIT 1";
					$params = array ( 'login' => trim($_POST['login']) );
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;	
					
					if ( $numRows > 0)	
					{
						$message .= show_msg ('err', $ERR_user_exists . ' [' . $_POST['login'] . ']');
					}
					else
					{			
						if ($_POST['active'] == '') 
							$_POST['active'] = 0;
						else
							$_POST['active'] = 1;						
						
						$sql = "INSERT INTO `" . $dbTables['users'] . "` VALUES ('', ?, ?, ?, ?, ?, '', ?, ?)";	

						$f_pass = sha1($_POST['passwd'].$salt);		
						$params = array (
									'name' => $_POST['name'], 
									'login' => $_POST['login'], 
									'passwd' => $f_pass, 
									'type' => 'user',
									'email' => $_POST['email'], 
									'last_visit' => '',
									'active' => $_POST['active'] 
									);
						$res->bind_execute( $params, $sql);
						$numRows = $res->numRows;		
						if ( $numRows > 0)	
						{	
							$message .= show_msg ('msg', $MSG_add);
							monitor( $_SESSION['userData']['UID'], $MON_user_add . $_POST['name'] , get_ip() );	
				
							if ($_POST['save']) {
								$showAddForm = false;
								$showList = true;
							}
						}
					}
				}
			}	
			
			if (isset($_POST['saveAdd']) || !isset($_POST['save'])) {
				$showAddForm = true;
			}
								
		}	
		
		/**
		 * Edycja uzytkownika
		 */
		if ($_GET['action'] == 'edit')
		{
			$showEditForm = true;	
			$showList = false;								
			
			if ($_GET['act'] == 'editPoz')
			{
				if ($_POST['active'] == '') 
					$_POST['active'] = 0;
				else
					$_POST['active'] = 1;
				
				if (!$_POST['name'] || !$_POST['login'])
				{
					$message .= show_msg ('err', $ERR_all_fields);
				}
				else if ($_POST['passwd'] != $_POST['passwd2'])
				{
					$message .= show_msg ('err', $ERR_passwd_eq);
				}
				else if (!check_email_addr($_POST['email']))
				{
					$message .= show_msg ('err', $ERR_email);
				} 				
				else
				{
					if (trim($_POST['passwd']) != '')
					{
						if (strlen(trim($_POST['passwd'])) < $passLength)
						{
							$message .= show_msg ('err', $ERR_passwd_min . $passLength);
						}
						else
						{							
							$sql = "UPDATE `" . $dbTables['users'] . "` SET name = ?, login = ?, passwd = ?, email = ?, active = ? WHERE (`id_user` = ?) LIMIT 1";

							$f_pass = sha1($_POST['passwd'].$salt);		
							$params = array (
										'name' => $_POST['name'], 
										'login' => $_POST['login'], 
										'passwd' => $f_pass,
										'email' => $_POST['email'], 										
										'active' => $_POST['active'],
										'id' => $_GET['id'] 
										);							
							
							$res->bind_execute( $params, $sql);
							$numRows = $res->numRows;		
							if ( $numRows > 0)	
							{		
								$message .= show_msg ('msg', $MSG_edit);
								monitor( $_SESSION['userData']['UID'], $MON_user_edit . $_POST['name'] , get_ip() );
								$showEditForm = false;
								$showList = true;	
							}	
						}
					}
					else
					{
						$sql = "UPDATE `" . $dbTables['users'] . "` SET name = ?, login = ?, email = ?, active = ? WHERE (`id_user` = ?) LIMIT 1";
						
						$params = array (
									'name' => $_POST['name'], 
									'login' => $_POST['login'],
									'email' => $_POST['email'],									
									'active' => $_POST['active'],
									'id' => $_GET['id'] 
									);
						
						$res->bind_execute( $params, $sql);
						$numRows = $res->numRows;	
						if ( $numRows > 0)	
						{		
							$message .= show_msg ('msg', $MSG_edit);
							monitor( $_SESSION['userData']['UID'], $MON_user_edit . $_POST['name'] , get_ip() );	
							$showEditForm = false;
							$showList = true;								
						}														
					}					
				}			
			}
			
			$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`id_user`= ?) LIMIT 1";
			$params = array ('id' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$row = $res->data[0];	
		}
		
		/**
		 * De-Aktywacja
		 */
		if ($_GET['action'] == 'noactive')
		{
			$sql = "UPDATE `" . $dbTables['users'] . "` SET active = ? WHERE (`id_user` = ?) LIMIT 1";
			$params = array (
							'active' => 0, 
							'id' => $_GET['id']
							);
			$res->bind_execute( $params, $sql);
		}
		
		/**
		 * Aktywacja
		 */
		if ($_GET['action'] == 'active')
		{
			$sql = "UPDATE `" . $dbTables['users'] . "` SET active = ? WHERE (`id_user` = ?) LIMIT 1";
			$params = array (
							'active' => 1, 
							'id' => $_GET['id']
							);
			$res->bind_execute( $params, $sql);
		}
		
		/**
		 * Usuwanie uzytkownika
		 */
		if ($_GET['action'] == 'delete')
		{
			$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`id_user` = ?) LIMIT 1";
			$params = array ( 'id_user' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			
			$userName = $res->data[0]['name'];
			
			$sql = "DELETE FROM `" . $dbTables['users'] . "` WHERE (`id_user` = ?) LIMIT 1";
			$params = array ('id_user' => $_GET['id']);
			$res->bind_execute( $params, $sql);
			$numRows = $res->numRows;	

			if ( $numRows > 0)		
			{		
				$sql = "DELETE FROM `" . $dbTables['priv'] . "` WHERE (`id_user` = ?) LIMIT 1";
				$params = array ('id_user' => $_GET['id']);
				$res->bind_execute( $params, $sql);
							
				$message .= show_msg ('msg', $MSG_del);
				monitor( $_SESSION['userData']['UID'], $MON_user_del . $userName , get_ip() );	
			}			
		}	
								
		/**
		 * Wypisanie wszystkich uzytkownikow
		 */	
		//if (!$_GET['action'] || in_array($_GET['action'],$actionUserList))
		if ($showList)
		{	 
			//$showList = true;
			
			$sql = "SELECT * FROM `" . $dbTables['users'] . "` ";
			$params = array();
			$res->bind_execute( $params, $sql);
			$outRow = $res->data;	
			$numRows = $res->numRows;	
		}		
	}
}
?>