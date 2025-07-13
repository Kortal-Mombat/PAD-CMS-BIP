<?php
if ($showPanel)
{
	if (get_priv_controler($_GET['c']))
	{	
		// zamiana na encje dla wybranych zmiennych
		$specialCharsNames = array ('pagename');
		foreach ($_POST as $k => $v)
		{
			if (in_array($k, $specialCharsNames))
			{
				$_POST[$k] = htmlspecialchars(strip_tags($v), ENT_QUOTES);		
			}
		}

		// zamiana na encje dla wybranych zmiennych
		$specialCharsNames = array ('metaTitle', 'metaKey', 'metaDesc');
		foreach ($_POST as $k => $v)
		{
			if (in_array($k, $specialCharsNames))
			{
				$_POST[$k] = strtr($v, $cmsConfig['replace_char_meta']);	
			}
		}	
		
		$TEMPL_PATH = CMS_TEMPL . DS . 'settings.php';
		
		setCSS('jquery.ui.theme.css', $css);
		setCSS('jquery.ui.tabs.css', $css);
	
		$res = new resClass;
		
		$pageTitle = 'Ustawienia ogólne';
		$crumbpath[] = array ('name' => $pageTitle, 'url' => $PHP_SELF . '?c=settings');

		$_GET['id'] = 1;
		
		/**
		 * Edycja
		 */
		if(isset($_POST['save']))
		{
			$numRows = 0;
			foreach($_POST as $key => $value)
			{
				if ($key != 'save')
				{
					if ($key != 'logo' && $key != 'address' && $key != 'activeTextWww') {
						$value = strip_tags($value);
					}
					
					if ($key == 'host')
					{
						$replace = ['http://','https://'];
						$value = str_replace($replace, '', $value);
					}
					
					$sql = "UPDATE `" . $dbTables['settings'] . "` SET attrib = ? WHERE (`id_name` = ?) LIMIT 1";	
					$params = array (
								'attrib' => $value, 
								'id_name' => $key
								);										
					$res->bind_execute( $params, $sql);
					$numRows .= $res->numRows;	
				}
			}

			if ($numRows > 0)	
			{		
				$message .= show_msg ('msg', $MSG_edit);
				monitor( $_SESSION['userData']['UID'], $MON_settings_edit , get_ip() );
			}
			
			$sql = "SELECT * FROM `" . $dbTables['settings'] . "` WHERE (`id_name`= 'editor') LIMIT 1";
			$params = array ();
			$res->bind_execute( $params, $sql);
			$tinyVersion = $res->data[0]['attrib'];									
		}
								
		/**
		 * Pobranie 
		 */
		$sql = "SELECT * FROM `" . $dbTables['settings'] . "` WHERE (`lang`= ?) ORDER BY id_set";
		$params = array ('lang' => $lang);
		$res->bind_execute( $params, $sql);
		$outRow = $res->data;	
		$numRows = $res->numRows;	
	}
	else
	{
		$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
		$message .= show_msg ('err', $ERR_priv_access);	
	}
}
?>