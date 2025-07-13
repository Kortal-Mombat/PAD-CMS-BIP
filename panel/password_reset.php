<?php
	$TEMPL_PATH = CMS_TEMPL . DS . 'password_reset.php';
	$pageTitle = 'Zresetuj hasło';
	$showForm = true;
	
	/**
	 * Prosba resetu hasła
	 */	
	if (isset($_GET['action']) && $_GET['action'] == 'pswd_reset') 
	{
		
		$f_email = $_POST['form_email'];
		
		$res = new resClass;
			
		$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`email` = ? ) AND (`email` != '' ) LIMIT 1";
		$params = array( 'email' => $f_email );
		$res->bind_execute( $params, $sql);
		
		$numRows = $res->numRows;	
		
		if ($numRows > 0)
		{
			$r = $res->data[0];	
			
			$auth_key = sha1(time().$salt);	
			
			$sql = "UPDATE `" . $dbTables['users'] . "` SET auth_key = ? WHERE (`id_user` = ?) LIMIT 1";
			$params = array (
						'auth_key' => $auth_key, 
						'id_user' => $r['id_user'], 
			);							
			
			$res->bind_execute( $params, $sql);
			$numRows = $res->numRows;		
			if ( $numRows > 0)	
			{		
				monitor( $r['id_user'], $MON_mail_pswd_reset . $r['name'] , get_ip() );
				
				$to  = $r['email'];
				$subject = 'Ustawienie nowego hasła - ' .$pageInfo['host'];					

				$content  = '<h1>Została wysłana do Ciebie prośba o zmianę hasła z adresu ' . $_SERVER['SERVER_NAME'] . '</h1>' . "\r\n";
				$content .= '<p><b>Nazwa uzytkownika:</b> '. $r['name'] .'</p>' . "\r\n";
				$content .= '<p>Jeśli nie wysyłałeś żądania zmiany hasła zignoruj tego e-maila.</p>' . "\r\n";
				$content .= '<p>Aby zmianić hasło przejdź na nastepujący adres: <a href="http://' . $pageInfo['host'] . '/panel/index.php?c=password_reset&action=reset&user='.$r['login'].'&ak='.$auth_key.'">http://' . $pageInfo['host'] . '/panel/index.php?c=password_reset&action=reset&user='.$r['login'].'&ak='.$auth_key.'</a></p>' . "\r\n";
	
				$content .= '<p><b>IP nadawcy:</b> '.get_ip().'</p>' . "\r\n";
				
				$sm = send_mail( $to, $subject, $content);

				if ($sm) 
				{
					$message .= show_msg ('msg', $MSG_mail_pswd_reset);
				}	
				else
				{
					$message .= show_msg ('err', $ERR_reset_pswd_email_send); 				
				}				
			}				
		} 
		else
		{
			$message .= show_msg ('err', $ERR_reset_pswd_email);
		}			
	}

	/**
	 * Reset hasła
	 */	
	if (isset($_GET['action']) && $_GET['action'] == 'reset') 
	{
		$_GET['ak'] = clean ($_GET['ak'], 64);
			
		$res = new resClass;
			
		$sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`login` = ? ) AND (`auth_key` = ? ) LIMIT 1";
		$params = array( 
			'login' => $_GET['user'],
			'auth_key' => $_GET['ak'],			
		);
		$res->bind_execute( $params, $sql);
		$r = $res->data[0];	
		
		$numRows = $res->numRows;	
		
		if ($numRows > 0)
		{
			$TEMPL_PATH = CMS_TEMPL . DS . 'password_reset_form.php';
			
			if ($_POST['send_reset'])
			{
				if (trim($_POST['passwd']) == '')
				{
					$message .= show_msg ('err', $ERR_passwd_inst);
				}
				else if (strlen(trim($_POST['passwd'])) < $passLength)
				{
					$message .= show_msg ('err', $ERR_passwd_min . $passLength);
				}				
				else if ($_POST['passwd'] != $_POST['passwd2'])
				{
					$message .= show_msg ('err', $ERR_passwd_eq);
					$showForm = true;
				}
				else
				{
					$auth_key = sha1(time().$salt);	
					$f_pass = sha1($_POST['passwd'].$salt);		
					
					$sql = "UPDATE `" . $dbTables['users'] . "` SET passwd = ?, auth_key = ? WHERE (`id_user` = ?) LIMIT 1";
					$params = array (
								'passwd' => $f_pass,
								'auth_key' => $auth_key, 
								'id_user' => $r['id_user'], 
					);							
					
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;		
					if ( $numRows > 0)	
					{
						monitor( $r['id_user'], $MON_mail_pswd_reset_ok . $r['name'] , get_ip() );
						$message .= show_msg ('msg', $MSG_confirm_pswd_reset); 
						$showForm = false;						
					}
					else
					{
						$message .= show_msg ('err', $ERR_err . $ERR_contact); 				
					}												
				}
			}			
		}
		else
		{
			$message .= show_msg ('err', $ERR_reset_pswd_key);
		}

	}
?>