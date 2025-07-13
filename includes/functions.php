<?php
$_POST['data_utw'] = $_POST['data_utw'] ?? date("Y-m-d H:i:s");
$_POST['data_publ'] = $_POST['data_publ'] ?? date("Y-m-d H:i:s");
/**
 * Sprawdzenie wersji php oraz czy usunieto instalke
 */
function check_install_files () 
{
	global $php_version;
	if (version_compare(PHP_VERSION, $php_version, '<'))
	{
		die('Potrzebujesz PHP w wersji '.$php_version.' lub wyższej aby uruchomić tą wersję PAD CMS.');
	}
		
	if (file_exists('install'))
	{
		die('Uwaga! Katalog "install" znajduje się w głównym katalogu. Jeśli strona została zainstalowana musisz usunąć katalog. W przeciwnym wypadku uruchom instalację wpisując adres Twojej strony dodając na końcu"/install" np.: <a href="http://'.$_SERVER['HTTP_HOST'].'/install">'.$_SERVER['HTTP_HOST'].'/install</a>.');
	}
}

/**
 * Sprawdzenie konfiguracji zmiennych do bazy
 */
function check_config () 
{
	global $dbConfig;
		
	if ($dbConfig['host'] == ''	&& $dbConfig['user'] == '' && $dbConfig['pass'] == '' && $dbConfig['dbname'] == '')
	{
		return false;
	}
	else
	{
		return true;
	}
}

/**
 * Dodanie wpisu do rejestru zmian
 */
function add_to_register ($id_art, $page_type) 
{
    global $dbTables;
    $res = new resClass;
		
	$_POST['save_opis_zm'] = $_POST['save_opis_zm'] ?? '';
	
    if ($_POST['save_opis_zm']=='on' && trim($_POST['opis_zm'])!='')
    {
		$sql = "INSERT INTO `" . $dbTables['register'] . "` VALUES ('', ?, ?, '', ?, ?, ?, ?, ?, ?)";
				
		switch ($page_type) {
				
			case 'article' : 
			$params = array (
				'idp' => 0, 
				'idg' => $id_art, 
				'os_sporz' => strip_tags($_POST['autor']),
				'os_wprow' => strip_tags($_POST['wprowadzil']),
				'data_utw' => $_POST['data_utw'],
				'data_publ' => $_POST['data_publ'], 
				'old_text' => $_POST['old_text'], 
				'akcja' => strip_tags($_POST['opis_zm'])
			);					
			break;
					
			case 'page' : 
			$params = array (
				'idp' => $id_art, 
				'idg' => 0, 
				'os_sporz' => strip_tags($_POST['autor']),
				'os_wprow' => strip_tags($_POST['wprowadzil']),
				'data_utw' => $_POST['data_utw'],
				'data_publ' => $_POST['data_publ'], 
				'old_text' => $_POST['old_text'], 				
				'akcja' => strip_tags($_POST['opis_zm'])
			);					
			break;				
		}
				
		$res->bind_execute( $params, $sql);					
		$numRows = $res->numRows;		
	
		if ( $numRows > 0)
		{	
			return true;
		}
		else 
		{
			return false;
		}			
    } 
	else
    {
		return false;
    }
}

/**
* Normalna data
*/
function showDateMonth ($data) {
	global $calendarMonth;
	
	$tmp = explode ('-', $data);
	$month = mb_strtolower($calendarMonth[set_to_int($tmp[1])], 'UTF-8');
	$data = (int)$tmp[2] .' '. $month .' ' . $tmp[0];
	return $data;
}

/**
* wyświetlenie modułów
*/
function show_modules ($mod_names, $modules) 
{
	$mod_list = array();
	$mod_list = explode(',', $mod_names);

	foreach ($mod_list as $k => $v)
	{
		$v = trim($v);
		if ($modules[$v]['active'] == 1)
		{
			echo '<div id="'.$modules[$v]['mod_name'].'" class="module">';
				echo '<div class="module_top"></div>';
				echo '<h2>'.$modules[$v]['name'].'</h2>';
				echo '<div class="module_icon"></div>';
				echo '<div class="module_content">' . get_module ($modules[$v]['mod_name']) . '</div>';
				echo '<div class="module_bottom"></div>';
			echo '</div>';			
		}
	}
}

/**
* Sorotwanie tablicy wielowymiarowej asocjacyjnej wg klucza
*/
	
function sortujTabliceWielowymiarowa($array, $cols) 
{
	$colarr = array();
	foreach ($cols as $col => $order) 
	{
		$colarr[$col] = array();
		foreach ($array as $k => $row) 
		{ 
			$colarr[$col]['_'.$k] = strtolower($row[$col]); 
		}
	}
	$eval = 'array_multisort(';
	foreach ($cols as $col => $order) 
	{
		$eval .= '$colarr[\''.$col.'\'],'.$order.',';
	}
	$eval = substr($eval,0,-1).');';
	eval($eval);
	$ret = array();
	foreach ($colarr as $col => $arr) 
	{
		foreach ($arr as $k => $v) 
		{
			$k = substr($k,1);
			if (!isset($ret[$k])) {
				$ret[$k] = $array[$k];
			}
			$ret[$k][$col] = $array[$k][$col];
		}
	}
	return $ret;
}
		 
/**
* Zwijanie wiersza po danej frazie
*/
function word_wrap($array, $text) 
{
     foreach ($array as $k => $v)
	 {
	 	$array2[$k] = str_replace(array('|', ' | ', '| ', ' |'), '', '<span class="br"></span>' . $v);
	 }
	 return str_replace( $array, $array2, $text );
}


function remote_file_exists($filename) 
{
    $ch = curl_init($filename);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($response_code == 200);
}

/**
* Otwarcie zdalnego pliku
*/
function read_online ($file_name)
{
    $plik =  fopen ($file_name,"r");
    if ($plik)
    {
	$wiersz="";
	while (!(feof($plik)))
	{
	    $wiersz .= (fgets($plik,255));
	}
	fclose($plik);
    }
    return $wiersz;
}

/**
* Pobranie informacji z zewnatrz
*/
function get_url_content ($url, $sessionName, $utf8 = false)
{
    if (!isset($_SESSION[$sessionName]))
    {
		$_SESSION[$sessionName] = read_online ($url);
    }
    if ($utf8)
    {
	return $_SESSION[$sessionName];
    } else
    {
	return iconv('iso-8859-2','utf-8',$_SESSION[$sessionName]);
    }
}
	
/**
* Licznik odwiedzin po (sesji)
*/
function my_counter() 
{
    global $dbTables;
		
    $res = new resClass;
				
    $sql = "SELECT * FROM `".$dbTables['counter']."` WHERE id='1' LIMIT 1";
    $params = array ();
    $res->bind_execute( $params, $sql);
    $c = $res->data[0];		
	
    if (!isset($_SESSION['counter']))
    {
	$c['count']++;
			
	$sql = "UPDATE `".$dbTables['counter']."` SET count='".$c['count']."'  WHERE id=1 LIMIT 1";
	$params = array ();
	$res->bind_execute( $params, $sql);			
	$_SESSION['counter'] = 1;
    }
    return $c['count'];
}
	
/**
 * Odliczanie do konca roku
 */
function my_year_end() 
{
    $d = date( 'd' );
    $m = date( 'm' );
    $y = date( 'Y' );

    $pDays = $d;
    for( $i = 1; $i < $m; $i++ ) {
	$pDays += cal_days_in_month( CAL_GREGORIAN, $i, $y );
    } // eof for()

    $days = 365;
    if( cal_days_in_month( CAL_GREGORIAN, 2, $y ) == '29' ) {
	$days++;
    } // eof if()

    $lDays = $days - $pDays;

    return $lDays;
}

function unsetXmlObject($array)
{
    $cleanArray = array();
    for ($i=0; $i<count($array); $i++)
    {
		if ($i==count($array)-1)
		{
			$help .= $array[$i];
		} 
		else
		{
			$help .= $array[$i].'{++}';
		}
    }
    $cleanArray = explode("{++}", $help);
    return $cleanArray;
}

	
/**
 * Pobranie zawartosci modułu
 */
function get_module ($module, $place = '') 
{
    global $dbTables, $lang, $TXT, $shortDate, $arrWeek, $userIP, $arrMonth, $showProtected, $external_text, $templateDir, $templateConfig, $clockType, $calendarMonth, $forumLogged;

    $res = new resClass;

    switch($module) 
    {
	case 'mod_questionnaire' : 
	    include('modules/mod_col_questionnaire.php');
	    return $txt;
	    break;

	case 'mod_stats' : 
	    include('modules/mod_col_stats.php');
	    return $txt;
	    break;

	case 'mod_calendar' : 
	    include('modules/mod_col_calendar.php');
	    return $txt;			
	    break;

	case 'mod_location' : 
	    include('modules/mod_col_location.php');	
	    return $txt;
	    break;					

	case 'mod_gallery' :
	    include('modules/mod_col_gallery.php');		
	    return $txt;
	    break;	

	case 'mod_contact' :
	    include('modules/mod_col_contact.php');
	    return $txt;
	    break;

	default : 
	    return false;
	    break;
    }	
}
	
/**
 * Pobranie sciezki okruszkow
 */
function get_crumb ($id)
{
    global $dbTables, $addcrumbpath;

    $res = new resClass;

    $sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`id`= ?) LIMIT 1";
    $params = array ('id' => $id);
    $res->bind_execute( $params, $sql);
    $row = $res->data[0];

    if (trim($row['ext_url']) != '')
    {
	$url = ref_replace($row['ext_url']);					
    } else
    {
	if ($row['url_name'] == '')
	{
	    $url = 'index.php?c=page&id='. $row['id'];
	} else
	{
	    $url = 'p,' . $row['id'] . ',' . trans_url_name($row['url_name']);
	}					
    }		
    $addcrumbpath[] = array ('name' => $row['name'], 'url' => $url);	

    if ($row['ref'] > 0)
    {
	get_crumb ($row['ref']);
    } 	
}
	
/**
 * Pobranie ścieżki do forum
 */
function getForumCrumb ($topicId)
{
    global $dbTables, $addcrumbpath;

    $res = new resClass();

    $sql = "SELECT * FROM `" . $dbTables['forumT'] . "` WHERE `id_topic` = ? LIMIT 1";
    $params = array(
	'id_topic' => $topicId
    );
    $res -> bind_execute($params, $sql);
    $row = $res -> data[0];

    $url = 'forum,s,' . $topicId . ',' . trans_url_name($row['topic']);

    $addcrumbpath[] = array ('name' => $row['topic'], 'url' => $url);

    if ($row['parent_id'] > 0)
    {
	getForumCrumb($row['parent_id']);
    }
}
		
/**
 * sprawdzenie czy tekst zawiera html
 */		
function check_html_text( $text, $allow_tags = '<table><img><iframe><object>' ) 
{
    if (trim(strip_tags($text,$allow_tags)) == '')
    {	
	return true;
    } else
    {
	return false;
    }
}
	
/**
 * Pobranie drzewka menu
 */

function get_menu_tree ($menutype, $ref = 0, $numline = 0, $sitemap = '', $firstLevel = false, $idName = '', $spans = false, $alternate = false, $various = false)
{
    global $dbTables, $lang, $TXT, $date, $depth, $page, $_GET, $templateConfig;

	$_GET['id'] = $_GET['id'] ?? 0;
	$_GET['c'] = $_GET['c'] ?? '';
	$page['id'] = $page['id'] ?? 0;

    $res = new resClass;

    $sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`ref` = ?) AND (`lang` = ?) AND (active ='1') ORDER BY pos";
    $params = array (
	'menutype' => $menutype,
	'ref' => $ref, 
	'lang' => $lang
    );
    $res->bind_execute( $params, $sql);

    if ($menutype == 'tm')
    {
	$menuClass = ' topMenu';
    } else
    {
	$menuClass = ' colMenu';
    }

    if ($res->numRows > 0)			
    {
	// jesli parametr sitemap to bez nadawania id dla <ul>
	if ($sitemap == 'sitemap')
	{
	    echo '<ul class="sitemap">';
	} else
	{
	    if ($ref == 0)
	    {
		if ($idName == '')
		{
		    $idName = $menutype;
		}
		echo '<ul class="'.$menutype.'_menu' . $menuClass . ' menus" id="'.$idName.'">';
	    } else
	    {
		echo '<ul class="'.$menutype.'_menu menus">';
	    }
	}

	$n = 0;
	foreach ($res->data as $row)
	{
	    $numline++;	
	    $target = $txt_target = $url = $url_title = $protect = $close_li = '';

	    $row['start_date'] = substr($row['start_date'], 0, 10);
	    $row['stop_date'] = substr($row['stop_date'], 0, 10);

	    if ( ($row['start_date'] <= $date && $row['stop_date'] >= $date) || ( $row['start_date'] == '0000-00-00' && $row['stop_date'] == '0000-00-00') )
	    {							
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
				$url_title = ' title="' . $TXT['new_window'] . '"';
		    }	
		    
		    $url = ref_replace($row['ext_url']);					
		} else
		{
		    if ($row['url_name'] == '')
		    {
			$url = 'index.php?c=page&amp;id='. $row['id'];
		    } else
		    {
			$url = 'p,' . $row['id'] . ',' . trans_url_name($row['url_name']);
		    }
		}
		$class = '';
		$last = ($res->numRows) - 1;
		if ($n == $last)
		{
		    $class = 'last';
		}
		if ($n == 0)
		{
		    $class = 'first';
		}
		
		if ($alternate)
		{
		    if ($various)
		    {
			if (strlen($row['name']) > $templateConfig['menuLong'])
			{
			    $long = 'Long';
			} else
			{
			    $long = 'Short';
			}
		    } else
		    {
			$long = '';
		    }
		    
		    if ($n % 2 == 0)
		    {
			$alternateClass = ' listEven' . $long;
		    } else
		    {
			$alternateClass = ' listOdd' . $long;
		    }
		} else
		{
		    $alternateClass = '';
		}
		
		$selected = '';
				
		if ( 
			(($_GET['id'] == $row['id']) && $_GET['c'] == 'page') || 
			(($page['id'] == $row['id']) && $_GET['c'] == 'article')
		)
		{
		    $depth = $numline;
		   
		    $selected = ' class="selected"';
		}

		echo '<li class="' . $class . $alternateClass . '">';
		
		echo '<a href="'. $url .'" ' . $url_title . $target . $selected . '>';
		if ($spans)
		{
		    echo '<span class="menuF">';
		}
		echo $row['name'] . $protect;
		if ($spans)
		{
		    echo '</span><span class="menuL"></span><br class="clear" />';
		}
	
		echo '</a>';
		$close_li = '</li>';
		$n++;
	    }

	    if (!$firstLevel)
	    {
		get_menu_tree ($menutype, $row['id'], $numline, $sitemap, false, '', false);
		$numline--;
	    }

	    echo $close_li;
	}
	echo '</ul>';
    }
    return $depth;
}	
	
/**
 * ustawienie danych usera panelu w sesji
 */		
function set_user_session( $r ) 
{
    global $cmsConfig;
    $_SESSION['userData'] = array();

    // czy potrzebne ????
    $_SESSION['userData']['SID'] = md5(uniqid(rand()));

    $_SESSION['userData']['UID'] = $r['id_user'];
    $_SESSION['userData']['name'] = $r['name'];
    $_SESSION['userData']['login'] = $r['login'];
    $_SESSION['userData']['type'] = $r['type'];
    $_SESSION['userData']['last_visit'] = $r['last_visit'];
    $_SESSION['userData']['active'] = $r['active'];		
    $_SESSION['userData']['ip'] = get_ip();	

    // zapamietanie uprawnien
    $_SESSION['userData']['privMenu'] = $r['privMenu'];	
    $_SESSION['userData']['privPages'] = $r['privPages'];
	$_SESSION['userData']['privMenuDyn'] = $r['privMenuDyn'];	
    $_SESSION['userData']['privControler'] = $r['privControler'];					
}
	
/**
 * ustawienie danych usera panelu w sesji
 */		
function set_user_page_session( $r ) 
{
    global $cmsConfig;
    $_SESSION['userPageData'] = array();


    $_SESSION['userPageData']['UID'] = $r['id_member'];
    $_SESSION['userPageData']['first_name'] = $r['first_name'];
    $_SESSION['userPageData']['last_name'] = $r['last_name'];
    $_SESSION['userPageData']['sex'] = $r['sex'];
    $_SESSION['userPageData']['email'] = $r['email'];				
    $_SESSION['userPageData']['login'] = $r['login'];
    $_SESSION['userPageData']['avatar'] = $r['avatar'];
    $_SESSION['userPageData']['protected'] = $r['protected'];
    $_SESSION['userPageData']['forum'] = $r['forum'];		
    $_SESSION['userPageData']['ip'] = get_ip();	
}
		
/**
 * sprawdzenie dostepu do controlera
 */		
function get_priv_controler( $c, $menuType = '' ) 
{
    global $dbTables;

    if ($_SESSION['userData']['type'] == 'admin') 
    {
		return true;
    } 
	else if (in_array($c, $_SESSION['userData']['privControler']))
    {
		if ($menuType != '')
		{
			$id_rec = implode(',', $_SESSION['userData']['privMenu']);
	
			$res = new resClass;
			$sql = "SELECT * FROM `" . $dbTables['menu_panel'] . "` WHERE (`controler` = ?) AND (`link` LIKE '%mt=".$menuType."%') AND (id_mp IN (".substr($id_rec,0,-1).")) LIMIT 1";
			$params = array('controler' => $c);
			$res->bind_execute( $params, $sql );	
			$numRows = $res->numRows;	
	
			if ($numRows > 0)
			{
				return true;
			} 
			else
			{
				return false;
			}
		} 
		else
		{
			return true;
		}
    } 
	else
    {
		return false;
    }
}
	
/**
 * sprawdzenie dostepu do controlera
 */		
function get_priv_pages( $page ) 
{
    global $dbTables;

    if ($_SESSION['userData']['type'] == 'admin') 
    {
		return true;
    } 
	else if ( in_array($page, $_SESSION['userData']['privPages']) )
    {
		return true;
    } 
	else
    {
		return false;
    }
}

/**
 * sprawdzenie dostepu do menu dyn
 */		
function get_priv_menudyn( $menuDyn ) 
{
    global $dbTables;

    if ($_SESSION['userData']['type'] == 'admin') 
    {
		return true;
    } 
	else if ( in_array($menuDyn, $_SESSION['userData']['privMenuDyn']) )
    {
		return true;
    } 
	else
    {
		return false;
    }
}
				 	
/**
 * wypisanie sciezki 
 */	
 function show_crumbpath($crumbpath, $sep)
 {
    if (is_array($crumbpath))
    {
	$i = 0;	
	foreach ($crumbpath as $k)
	{
	    if ($i != 0) {
		echo $sep;
	    }
	    if (trim($k['url']) != '') {
		echo '<a href="'.$k['url'].'">'.$k['name'].'</a>';
	    } else
	    {
		echo $k['name'];			
	    }
	    $i++;
	}
    } else
    {
	return false;
    }
 }

/**
 * sprawdzenie czy user jest zalogowany
 */		
function check_login_user()
{
    global $dbTables;

    $user_data = $_SESSION['userData'];
    $uid    = ( isset($user_data['UID']) && is_numeric($user_data['UID']) ) ? $user_data['UID']: NULL;

    $res = new resClass;

    $sql = "SELECT * FROM `" . $dbTables['users'] . "` WHERE (`id_user` = ?) LIMIT 1";
    $params = array('id_user' => $uid);

    $res->bind_execute( $params, $sql );
    $numRows = $res->numRows;	

    if ( $uid == '' || $numRows <= 0 ) 
    {
		return false;
    } 
	else 
    {
	    // porownanie zapisanego numeru sesji w monitorze z zapamietanym w sesji
		$sql = "SELECT * FROM `" . $dbTables['monitor'] . "` WHERE (`id_user` = ?) AND (`action` LIKE 'Zalogowanie użytkownika==%') ORDER BY date DESC LIMIT 1";
    	$params = array('id_user' => $uid);
	    $res->bind_execute( $params, $sql );		
		
		$tmp = explode('==', $res->data[0]['action']);
		$sessID = $tmp[1];

	    // porownanie zapisanego numeru sesji w monitorze bipu z zapamietanym w sesji
		$sql = "SELECT * FROM `" . $dbTables['monitor'] . "` WHERE (`id_user` = ?) AND (`action` LIKE 'BIP - Zalogowanie użytkownika==%') ORDER BY date DESC LIMIT 1";
    	$params = array('id_user' => $uid);
	    $res->bind_execute( $params, $sql );		
		
		if ($res->numRows != 0) {
			$tmp_bip = explode('==', $res->data[0]['action']);
			$sessIDbip = $tmp_bip[1];	
		}else {
			$sessIDbip = '';
		}
				
		if ($sessID == $_SESSION['userData']['SID'] || $sessIDbip == $_SESSION['userData']['SID'])
		{
			return true;
		}
		else
		{
			return false;		
		}
    }
}		

/**
 * sprawdzenie czy user jest zalogowany na www
 */		
function check_login_user_page()
{
    global $dbTables;

    $user_data = $_SESSION['userPageData'];
    $uid    = ( isset($user_data['UID']) && is_numeric($user_data['UID']) ) ? $user_data['UID']: NULL;

    $res = new resClass;

    $sql = "SELECT * FROM `" . $dbTables['members'] . "` WHERE (`id_member` = ?) LIMIT 1";
    $params = array('id_member' => $uid);

    $res->bind_execute( $params, $sql );
    $numRows = $res->numRows;	

    if ( $uid == '' || $numRows <= 0 ) 
    {
	    return false;
    } else 
    {
	    return true;
    }
}	
	

/**
 * monitor
 */		
function monitor($uid, $action = '', $ip = '')
{
    global $dbTables, $date;

    $res = new resClass;

    $sql = "INSERT INTO `" . $dbTables['monitor'] . "` VALUES (DEFAULT, ?, ?, ?, ? )";
    $params = array(
	'id_user' => $uid,
	'date' => $date,
	'action' => $action,
	'ip' => $ip
    );
    $res->bind_execute( $params, $sql);
}		

/**
 * Przygotowanie maila
 */		
function send_mail( $to, $subject, $body, $fromName = '', $fromEmail = '' )
{
    global $pageInfo;

    if ($fromName == '') {
		$fromName = $pageInfo['name'];
    }

    if ($fromEmail == '') {
		$fromEmail = $pageInfo['email'];
    }

    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

    $message = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n";
    $message .= '<style type="text/css">body {background:#fff; color:#000;} img {border:none;}</style>';
    $message .= "</head><body>\r\n";
    $message .= "<div align=\"left\" style=\"padding:10px;\">\r\n";

    $message .= $body;

    $message .= "</div></body></html>\r\n";

    $headers  = "MIME-Version: 1.0\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= "From:" . $fromName . " <" . $fromEmail . ">\r\n";

    if (mail($to, $subject, $message, $headers))
    {
		return true;
    } 
	else
    {
		return false;
    }
}	
			
/**
 * Debugowanie zmiennych 
 */ 
function debug( $item = '', $toDie = '0' ) 
{
    print '<pre>';
    var_dump( $item );
    print '</pre>';

    if( $toDie == '1' ) 
    {
	die();
    }
}

		
/** 
 * Funkcja usuwajaca z sesji. 
 */
function del_session( $what = '' ) 
{
    if( ( $what != '' ) && ( isset( $_SESSION[$what] ) ) ) 
    {
	unset( $_SESSION[$what] );
    } 
} 

/** 
 * Funkcja czyszczaca sesje. 
 */
function clean_session() 
{
    $session = $_SESSION;
    foreach( $session as $key => $value ) 
    {
	unset( $_SESSION[$key] );
    } 
    $_SESSION['userData'] = 'empty';
}
	
function clean_page_session() 
{
    $_SESSION['userPageData'] = 'empty';
}	
	
/** 
 * Funkcja czyszczaca zmienne. 
 */				
function conv_vars ($in , $tags = '') 
{
    if (is_string($in)) {
        $out = strip_tags($in, $tags);
        $out = trim(htmlspecialchars($out, ENT_QUOTES, 'UTF-8'));
        return $out;
    } elseif (is_array($in)) {
        $out = [];
        foreach ($in as $key => $val) {
            $out[$key] = conv_vars($val, $tags); // rekurencja
        }
        return $out;
    } else {
        return '';
    }
}
	
/** 
 * Funkcja czyszczaca zmienne. 
 */		
function clean($str, $length = 32)
{
    $trans = ["'" => "", "\\" => "", "\"" => "", ".." => ""];

    // Jeśli to tablica — czyść każdy element rekurencyjnie
    if (is_array($str)) {
        $cleaned = [];
        foreach ($str as $key => $value) {
            $cleaned[$key] = clean($value, $length);
        }
        return $cleaned;
    }

    // Jeśli to nie string — konwertuj na string
    if (!is_string($str)) {
        $str = (string)$str;
    }

    // Właściwe czyszczenie
    $clr = trim(substr(strtr($str, $trans), 0, $length));
    return $clr;
} 

/** 
 * Funkcja czyszczaca zmienne - tylko numeryczne. 
 */	
function clean_id($str)
{
    if (is_numeric($str))
    {
	return $str;
    } else
    {
	header('Location: '.url_redirect ('index.php'));
    }
} 

/** 
 * Usuwanie ogonków itp z ciągu. 
 */	
function trans_url_name ($str)
{
    global $cmsConfig;
    $url = strtolower(strtr(trim($str), $cmsConfig['replace_char']));
    return $url;
}
	
/**
 * Usuwanie ogonków ale bez zmiany wielkości liter - do plików
 */
function trans_url_name_may ($str)
{
    global $cmsConfig;

    $replaceArr = $cmsConfig['replace_char'];
    array_shift($replaceArr);
    $url = strtr(trim($str), $replaceArr);
    return $url;
}	

/** 
 * Funkcja dodaje pliki javascriptu, ktore maja byc odpalone dla metody kontrolera. 
 */
function setJS( $params = '', &$js ) 
{
    if( $params != '' && !in_array( $params, $js ) ) 
    {
		$js[] = $params;
    } 
}
	
/** 
 * Funkcja dodaje pliki css, ktore maja byc odpalone dla metody kontrolera. 
 */
function setCSS( $params = '', &$css ) 
{
    if( $params != '' && !in_array( $params, $css ) ) {
		$css[] = $params;
    } 
}
			
/**
 * pobranie rozszezrenia pliku
 */ 	
function getExt($str) 
{
    return strtolower(substr(strrchr($str, '.'),1));
}	
	
/**
 * pokazanie komunikatu lub bledu 
 */ 
function show_msg ( $type, $message )
{
    if ($type == 'msg')
    {
	return '<div class="txt_msg" role="alert">' . $message . '</div>';
    }
    if ($type == 'err')
    {
	return '<div class="txt_err" role="alert">' . $message . '</div>';
    }
    if ($type == 'info')
    {
	return '<div class="txt_com">' . $message . '</div>';
    }
} 		

/**
 * konwersja na integer 
 */ 
function set_to_int( $str )
{
    settype( $str, "integer" );
    return $str;
} 	
		
/**
 * url do przekierowania 
 */ 
function url_redirect ($link)
{
    $url = '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/'. $link;
    return $url;
}			
	

/**
 * Pobranie ip 
 */ 	
function get_ip($onlyIP = '')
{
	$ip_f = '';
    if ($onlyIP == 'onlyIP')
    {
	if (empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
	    $ip = $_SERVER["REMOTE_ADDR"];
	} else 
	{
	    $ip =  $_SERVER["HTTP_X_FORWARDED_FOR"];
	}			
	return $ip;
    } else
    {
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
	{
	    $ip_f = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	$ip = $_SERVER["REMOTE_ADDR"]; 
	$host = gethostbyaddr( $ip );

	return $ip . ' | ' . $host . ' | ' . $ip_f;
    }
}
	
/**
 * Przedstawienie bajtów skróconej formie  
 */ 	
function resize_bytes($size)
{
    $count = 0;
    $format = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
    while(($size/1024)>1 && $count<8)
    {
	$size=$size/1024;
	$count++;
    }
    $return = number_format($size, 2, '.', ' ')." ".$format[$count];
    return $return;
}	
	
/**
 * Rozmiar pliku  
 */ 	
function file_size($file)
{
    return resize_bytes(filesize($file));
}
	
/**
 * Usuniecie znacznika BOM z pliku utf  
 */ 	
function remove_BOM($s)
{
    if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF')))
    {
       return substr($s,3);
    } else
    {
       return $s;
    }
}	
	
/**
 * Przycinanie tekstu do zadanej ilosci znaków
 */ 	
function txt_truncate($string, $length = 200, $etc = ' [...]', $break_words = false, $middle = false)
{
    if ($length == 0)
    {
	return '';
    }

    if (strlen($string) > $length) 
    {
	$length -= min($length, strlen($etc));
	if (!$break_words && !$middle) 
	{
	    $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
	}
	if(!$middle) 
	{
	    return substr($string, 0, $length) . $etc;
	} else 
	{
	    return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
	}
    } else 
    {
	return $string;
    }
} 

/**
 * Zamiana ampersandow
 */ 
function ref_replace($str)
{
    return str_replace('&', '&amp;', $str);
}

/**
 * Generowanie ciagu znakow dla hasel
 */ 
function gen_pass($seed, $num = 8)
{
    return substr(md5($seed), rand(0,10), $num);
}	

/**
 * Sprawdzenie poprawnosci adresu mail
 */ 	
function check_email_addr($email) 
{
    if (preg_match('/^[a-zA-Z0-9.\-_]+\@[a-zA-Z0-9.\-_]+\.[a-z]{2,4}$/D', $email))
    {
	return 1;
    } else
    {
	return 0;
    }
}	
	
/**
 * Zapisanie do pliku
 */ 	
function write_file($file_name,$tekst,$mode) 
{
    $plik = @ fopen ($file_name,$mode);
    if ($plik)
    {
	flock($plik,2);
	fputs($plik,$tekst);
	flock($plik,3);
	fclose($plik);
    } 
}

/** 
 * Funkcja usuwajaca pliki 
 */
function del_file( $file, $source ) 
{
	global $lang;
	switch ($source)
	{

	    case 'download': 
		unlink ('../download/'.$file);		
		break;

	    case 'photos': 
		unlink ('../files/'.$lang.'/'.$file);	
		//unlink ('../files/'.$lang.'/midi/'.$file);	
		unlink ('../files/'.$lang.'/mini/'.$file);	
		break;

	    case 'container': 
		unlink ('../container/'.$file);		
		break;					
	}
} 
	
/**
 * Odczytanie z pliku
 */ 	
function read_file ($file_name) 
{
    $plik = @ fopen ($file_name,"r");
    if ($plik)
    {
	flock($plik,2);
	$wiersz="";
	while (!(feof($plik)))
	{
	    $wiersz .= (fgets($plik,255));
	}
	flock($plik,3);
	fclose($plik);
    }
    return $wiersz;
}
	
/**
 * Funkcja pobiera wszystkie pozycje menu do selecta
 */	
function get_menu ($menu, $ref, $numline, $selected = 0, $depth = 3)
{
	if (count($menu)>0)
    {
		foreach ($menu as $row)
		{		
			if (get_priv_pages($row['id']))
			{
				$sel = '';
		
				if ($selected == $row['id'])
				{
					$sel = ' selected="selected"';
				}
		
				if ($row['ref'] == $ref)
				{					
					$numline++;	
		
					if ($row['ref'] == 0)
					{
						$spacja = '';
					} 
					else
					{
						$spacja = '|';
					}
		
					for ($i=0; $i<$numline-1; $i++)
					{
						$spacja .= '-&nbsp;' ;
					}
		
					echo '<option value="' . $row['id'] . '" ' . $sel . '>' . $spacja . ' ' . $row['name'] . '</option>';
					
					if ($numline < $depth)
					{
						get_menu ($menu, $row['id'], $numline, $selected, $depth);
					}
					$numline--;
					$spacja = '';
				}
			}
		}
    }
}
		
/**
 * funkcja tworząca paginację, 
 * argumenty: liczba rekordów, rekordów na jedną stronę, liczba linków na pasku przez dwa, numer bieżącej strony
 */
function pagination ($l_odp, $l_odp_nastronie, $l_odp_napasku, $a) 
{ 
    $l_odp_podz = intval($l_odp/$l_odp_nastronie)+1;

    $l_odp_podz_mod = $l_odp%$l_odp_nastronie;

    if($l_odp_podz_mod>0)
    {
	++$l_odp_podz;
    }

    if($a>=$l_odp_podz)
    {
	$a=$l_odp_podz-1;
    }

    if($a>1)
    {
	    $tablica['prev']=$a-1;
    } else
    {
	    $tablica['prev']=0;
    }

    if($a<=$l_odp_napasku)
    {
        $koniec=$l_odp_napasku*2+2;
    } else
    {
        $koniec=$a+$l_odp_napasku+1;
    }

    if($a<=$koniec-$l_odp_napasku)
    {
	$star=$a-$l_odp_napasku;
    }

    if($a>=$l_odp_podz-$l_odp_napasku)
    {
	$star=$l_odp_podz-$l_odp_napasku*2-1;
    }

    if($koniec>$l_odp_podz)
    {
	$koniec=$l_odp_podz;
    }

    if($star<1)
    {
	$star=1;
    }

    for($i=$star;$i<$koniec;++$i)
    {
	if($i<$a)
	{
	    $tablica[]=$i;
	}

	if($i==$a)
	{
	    $tablica['active'] = $i;
	}

	if($i>$a)
	{
	    $tablica[]=$i;
	}    
    }

    if($a<$l_odp_podz-1)
    {
	$tablica['next']=$a+1;
    } else
    {
	$tablica['next']=0;
    }

    $tablica['start']=0;
    $tablica['end']=$l_odp_podz-1;

    return $tablica;
}
	
/**
 * Data
 */ 	
function showDate() {
    $days = array(
	1 => 'Poniedziałek',
	2 => 'Wtorek',
	3 => 'Środa',
	4 => 'Czwartek',
	5 => 'Piątek',
	6 => 'Sobota',
	7 => 'Niedziela'
    );

    $months = array(
	1 => 'Styczeń',
	2 => 'Luty',
	3 => 'Marzec',
	4 => 'Kwiecień',
	5 => 'Maj',
	6 => 'Czerwiec',
	7 => 'Lipiec',
	8 => 'Sierpień',
	9 => 'Wrzesień',
	10 => 'Październik',
	11 => 'Listopad',
	12 => 'Grudzień'
    );

    return $days[ date( 'N' ) ] . ', ' . date( 'j' ) . ' ' . $months[ date( 'n' ) ] . ' ' . date( 'Y' );
}
	
function showHumanDate($time = '')
{
    $days = array(
	1 => 'Poniedziałek',
	2 => 'Wtorek',
	3 => 'Środa',
	4 => 'Czwartek',
	5 => 'Piątek',
	6 => 'Sobota',
	7 => 'Niedziela'
    );	

    $months = array(
	1 => 'stycznia',
	2 => 'lutego',
	3 => 'marca',
	4 => 'kwietnia',
	5 => 'maja',
	6 => 'czerwca',
	7 => 'lipca',
	8 => 'sierpnia',
	9 => 'września',
	10 => 'października',
	11 => 'listopada',
	12 => 'grudnia'
    );
    
	if ($time == '')
    {
		return $days[ date( 'N' ) ] . ', ' . date( 'j' ) . ' ' . $months[ date( 'n' ) ] . ' ' . date( 'Y' );
    } 
	else
    {
		$time = strtotime($time);
		return $days[ date( 'N',  $time ) ] . ', ' . date( 'j', $time ) . ' ' . $months[ date( 'n', $time ) ] . ' ' . date( 'Y', $time );
    }
}

function imagecreatetruecolortransparent($x, $y){
    $i = imagecreatetruecolor($x, $y);
    $b = imagecreatefromstring(base64_decode(blankpng()));
    imagealphablending($i, false);
    imagesavealpha($i, true);
    imagecopyresized($i, $b ,0 ,0 ,0 ,0 ,$x, $y, imagesx($b), imagesy($b));
    return $i;
}
	
function blankpng(){
    $c = "iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m";
    $c .= "dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBNCg";
    $c .= "dyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAAN";
    $c .= "egcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQ";
    $c .= "oHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAA";
    $c .= "DXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII=";
    return $c;
}	
	 	
function imagemini($nazwapliku, $mx, $my, $mini, $maxi, $quality)
{
    if (substr($nazwapliku, -3)== 'jpg' || substr($nazwapliku, -3)== 'JPG')
    {
        $imgSrc = imagecreatefromjpeg ($maxi."/".$nazwapliku);
    }
    if (substr($nazwapliku, -4)== 'jpeg' || substr($nazwapliku, -4)== 'JPEG')
    {
        $imgSrc = imagecreatefromjpeg ($maxi."/".$nazwapliku);
    }	    
    if (substr($nazwapliku, -3)== 'png' || substr($nazwapliku, -3)== 'PNG')
    {
	$imgSrc = imagecreatefrompng ($maxi."/".$nazwapliku);
    }
    if (substr($nazwapliku, -3)== 'gif' || substr($nazwapliku, -3)== 'GIF')
    {
	$imgSrc = imagecreatefromgif ($maxi."/".$nazwapliku);
    }	
    $outWidth=$mx;
    $outHeight=$my;
    $srcWidth=imagesx($imgSrc);
    $srcHeight=imagesy($imgSrc);
    $imgOut=imagecreatetruecolortransparent($outWidth,$outHeight);
    if ($srcWidth>$srcHeight)
    {
	$ratio = (double)($srcHeight / $outHeight);
	$width = round($outWidth * $ratio);
	if ($width > $srcWidth)
	{
	    $ratio = (double)($srcWidth / $outWidth);
	    $width = $srcWidth;
	    $height = round($outHeight * $ratio);
	    $xOffset = 0;
	    $yOffset = round(($srcHeight - $height) / 2);
	} else 
	{
	    $height = $srcHeight;
	    $xOffset = round(($srcWidth - $width) / 2);
	    $yOffset = 0;
	}
    } else
    {
	$ratio = (double)($srcWidth / $outWidth);
	$height = round($outHeight * $ratio);
	if ($height > $srcHeight)
	{
	    $ratio = (double)($srcHeight / $outHeight);
	    $height = $srcHeight;
	    $width = round($outWidth * $ratio);
	    $xOffset = round(($srcWidth - $width) / 2);
	    $yOffset = 0;
	} else
	{
	    $width = $srcWidth;
	    $xOffset = 0;
	    $yOffset = round(($srcHeight - $height) / 2);
	}	
    }
    imagecopyresampled($imgOut, $imgSrc, 0, 0, $xOffset, $yOffset, $outWidth, $outHeight, $width, $height);
    if (substr($nazwapliku, -3)== 'jpg' || substr($nazwapliku, -3)== 'JPG')
    {
        imagejpeg($imgOut, $mini."/".$nazwapliku, 90);
    }
    if (substr($nazwapliku, -4)== 'jpeg' || substr($nazwapliku, -4)== 'JPEG')
    {
        imagejpeg($imgOut, $mini."/".$nazwapliku, 90);
    }	    
    if (substr($nazwapliku, -3)== 'png' || substr($nazwapliku, -3)== 'PNG')
    {
        imagepng($imgOut, $mini."/".$nazwapliku);
    }
    if (substr($nazwapliku, -3)== 'gif' || substr($nazwapliku, -3)== 'GIF')
    {
        imagegif($imgOut, $mini."/".$nazwapliku);
    }	
    imagedestroy($imgSrc);
    imagedestroy($imgOut);
}
	
function imagemax($nazwapliku, $maxWidth, $maxHeight, $mini, $maxi, $quality)
{
    if (substr($nazwapliku, -3)== 'jpg' || substr($nazwapliku, -3)== 'JPG')
    {
        $imgSrc = imagecreatefromjpeg ($maxi."/".$nazwapliku);
    }
    if (substr($nazwapliku, -4)== 'jpeg' || substr($nazwapliku, -4)== 'JPEG')
    {
        $imgSrc = imagecreatefromjpeg ($maxi."/".$nazwapliku);
    }	    
    if (substr($nazwapliku, -3)== 'png' || substr($nazwapliku, -3)== 'PNG')
    {
        $imgSrc = imagecreatefrompng ($maxi."/".$nazwapliku);
    }
    if (substr($nazwapliku, -3)== 'gif' || substr($nazwapliku, -3)== 'GIF')
    {
        $imgSrc = imagecreatefromgif ($maxi."/".$nazwapliku);
    }	
    $srcWidth=imagesx($imgSrc);
    $srcHeight=imagesy($imgSrc);

    $ratioX=$maxWidth/$srcWidth;	
    $ratioY=$maxHeight/$srcHeight;
    if (($srcWidth<=$maxWidth)&&($srcHeight<=$maxHeight)) 
    {
        $width=$srcWidth;
	$height=$srcHeight;	
    } else if (($ratioX*$srcHeight)<$maxHeight)
    {
        $width=$maxWidth;
        $height=round($ratioX*$srcHeight);
    } else 
    {
        $width=round($ratioY*$srcWidth);
	$height=$maxHeight;
    }
    $imgOut=imagecreatetruecolortransparent($width, $height);	
    imagecopyresampled($imgOut, $imgSrc, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
    if (substr($nazwapliku, -3)== 'jpg' || substr($nazwapliku, -3)== 'JPG')
    {
        imagejpeg($imgOut, $mini."/".$nazwapliku, $quality);
    }
    if (substr($nazwapliku, -4)== 'jpeg' || substr($nazwapliku, -4)== 'JPEG')
    {
        imagejpeg($imgOut, $mini."/".$nazwapliku, $quality);
    }	    
    if (substr($nazwapliku, -3)== 'png' || substr($nazwapliku, -3)== 'PNG')
    {
        imagepng($imgOut, $mini."/".$nazwapliku);
    }
    if (substr($nazwapliku, -3)== 'gif' || substr($nazwapliku, -3)== 'GIF')
    {
        imagegif($imgOut, $mini."/".$nazwapliku);
    }	
    imagedestroy($imgSrc);
    imagedestroy($imgOut);
}

function icon($extension) 
{
    switch($extension) 
    {
	case "jpg"  : $img = "fileImgIco"; break;
	case "png"  : $img = "fileImgIco"; break;
	case "bmp"  : $img = "fileImgIco"; break;
	case "gif"  : $img = "fileImgIco"; break;
	case "tif"  : $img = "fileImgIco"; break;
	case "tiff" : $img = "fileImgIco"; break;
	case "psd"  : $img = "fileImgIco"; break;

	case "xls"  : $img = "fileXlsIco"; break;
	case "ods"  : $img = "fileXlsIco"; break;

	case "pdf"  : $img = "fileTxtIco"; break;
	case "doc"  : $img = "fileTxtIco"; break;
	case "odt"  : $img = "fileTxtIco"; break;
	case "rtf"  : $img = "fileTxtIco"; break;
	case "txt"  : $img = "fileTxtIco"; break;

	case "ppt"  : $img = "fileImgIco"; break;
	case "odp"  : $img = "fileImgIco"; break;

	case "swf"  : $img = "fileSwfIco"; break;

	case "avi"  : $img = "fileMovIco"; break;
	case "mpg"  : $img = "fileMovIco"; break;
	case "mov"  : $img = "fileMovIco"; break;
	case "flv"  : $img = "fileMovIco"; break;
	case "wmv"  : $img = "fileMovIco"; break;
	case "mp4"  : $img = "fileMovIco"; break;

	case "mp3"  : $img = "fileMusIco"; break;
	case "wav"  : $img = "fileMusIco"; break;
	case "au"   : $img = "fileMusIco"; break;
	case "mid"  : $img = "fileMusIco"; break;

	case "zip"  : $img = "fileZipIco"; break;
	case "gz"   : $img = "fileZipIco"; break;
	case "tar"  : $img = "fileZipIco"; break;

	default     : $img = "fileDefIco"; break;
    }
    return $img;
}	
	
function removeRecurenceDir($dir)
{
    if (is_dir($dir)) 
    {
	$objects = scandir($dir);
	foreach ($objects as $object) 
	{
	    if ($object != "." && $object != "..") 
	    {
		if (filetype($dir."/".$object) == "dir") removeRecurenceDir($dir."/".$object); else unlink($dir."/".$object);
	    }
	}
	reset($objects);
	rmdir($dir);
    }
}

function formatFileSize($bytes, $decimals = 2){
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    $retStr = sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor];
    if ($factor > 0)
    {
        $retStr .= 'B';
    }
    return $retStr;
}

	
/*
 * attach TinyMCE
*/
function addEditor($textArea, $addStyle = '')
{
    global $lang, $js, $templateDir, $tinyVersion;
    
    if ($addStyle != '')
    {
		$addStyle = ', ../' . $templateDir . '/css/' . $addStyle . '.css?noc=' . time() .'';
    }
	
	if ($tinyVersion == 'TinyMCE 4')
	{
		$tmp = array();
		$tmp = explode(',', $textArea);
		foreach ($tmp as $k => $v)
		{
			$tmp[$k] = 'textarea#' . trim($v);
		}
		
		$textArea = implode(',', $tmp);
		
		$tiny = '<script type="text/javascript" src="template/js/tinymce4/tinymce.min.js"></script>
				<script type="text/javascript">
				tinymce.init({
					language : "pl",
					selector:"'.$textArea.'",
					browser_spellcheck: true,
					force_p_newlines : true,
					entities : "",
					entity_encoding : "raw",
					relative_urls : false,
					convert_urls : false,
					image_advtab: true,
					image_title: true,
					plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks code fullscreen",
						"insertdatetime media table contextmenu paste visualblocks"
					],
					toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | anchor link image media | table blockquote removeformat charmap | langbutton | code",
					menu : { 
						file   : {title : "File"  , items : "newdocument"},
						edit   : {title : "Edit"  , items : "cut copy paste pastetext | selectall | searchreplace"},
						insert : {title : "Insert", items : "link media | template hr"},
						view   : {title : "View"  , items : "visualaid | visualblocks"},
						format : {title : "Format", items : "bold italic underline strikethrough superscript subscript | formats | removeformat"},
						table  : {title : "Table" , items : "inserttable tableprops deletetable | cell row column"}
  					},
					setup: function (editor) {
						editor.addButton("langbutton", {
						text: "Język",
						title: "Wstaw atrybut lang",
						icon: false,
						onclick: function () {
							var el = editor.selection.getNode();
							var attrib = editor.dom.getAttrib(el, "lang");
							editor.windowManager.open( {
									title: "Wstaw atrybut \'lang\'",
									body: [{
										type: "textbox",
										name: "lang",
										label: "Język",
										value: attrib
									}],
									onsubmit: function( e ) {
										if (attrib != ""){
											editor.dom.setAttrib(el, "lang", e.data.lang);
										} else {
											editor.insertContent( "<span lang=\"" + e.data.lang + "\" >" + editor.selection.getContent() + "</span>");
										}
									}
								});							
						}
						});
					},										
					style_formats: [
						{title: "Headers", items: [
							{title: "Header 3", format: "h3"},
							{title: "Header 4", format: "h4"},
							{title: "Header 5", format: "h5"},
							{title: "Header 6", format: "h6"}
						]},
						{title: "Inline", items: [
							{title: "Bold", icon: "bold", format: "bold"},
							{title: "Italic", icon: "italic", format: "italic"},
							{title: "Underline", icon: "underline", format: "underline"},
							{title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
							{title: "Superscript", icon: "superscript", format: "superscript"},
							{title: "Subscript", icon: "subscript", format: "subscript"},
							{title: "Code", icon: "code", format: "code"}
						]},
						{title: "Blocks", items: [
							{title: "Paragraph", format: "p"},
							{title: "Blockquote", format: "blockquote"},
							{title: "Div", format: "div"},
							{title: "Pre", format: "pre"}
						]},
						{title: "Alignment", items: [
							{title: "Left", icon: "alignleft", format: "alignleft"},
							{title: "Center", icon: "aligncenter", format: "aligncenter"},
							{title: "Right", icon: "alignright", format: "alignright"},
							{title: "Justify", icon: "alignjustify", format: "alignjustify"}
						]}
					],

					content_css : "../' . $templateDir . '/css/style.css?noc=' . time() . ', ../' . $templateDir . '/css/fonts.css?noc=' . time() .', ../' . $templateDir . '/css/styleTiny.css?noc=' . time() . ', ../' . $templateDir . '/css/fonts.css?noc=' . time() . $addStyle .', template/css/styleTinyMCE.css?noc=' . time() . '",			
					file_browser_callback : myFileBrowser
				});';
				
		$tiny .= 'function myFileBrowser(field_name, url, type, win){' . 
		'var browserURL = "index.php?c=browser&type="+ type;' .
			'tinymce.activeEditor.windowManager.open({' .
			'file : browserURL,' .
			'title : "Przeglądarka plików",' .
			'width : 945,' .
			'height : 500,' .
			'resizable : "yes",' .
			'inline : "yes",' .
			'close_previous : "no"' .
		'}, {' . 
			'window : win,' .
			'input : field_name' .
		'});' . 
		'return false;' .
		'}';
	
		$tiny .= '</script>';				

	}

	if ($tinyVersion == 'TinyMCE 3')
	{
		$tiny = '<script type="text/javascript" src="template/js/tiny_mce/tiny_mce.js"></script>' . "\r\n" .
			'<script language="javascript" type="text/javascript">' . "\r\n" .
			'tinyMCE.init({' . "\r\n" .
			'language:"'.$lang.'", ' . "\r\n" .
			'mode:"exact", ' . "\r\n" .
			'elements:"'.$textArea.'", ' . "\r\n" .
			'theme:"advanced",' . "\r\n" .
			'force_p_newlines : true,' . "\r\n" .
			'entities : "",' . "\r\n" .
			'entity_encoding : "raw",' . "\r\n" .
			'relative_urls : false,' . "\r\n" .
			'convert_urls : false,' . "\r\n" .
			'plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",' . "\r\n" .
			'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",' . "\r\n" .
			'theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",' . "\r\n" .
			'theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",' . "\r\n" .
			'theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,blockquote",' . "\r\n" .
			'theme_advanced_blockformats : "p,div,h3,h4,h5,h6,blockquote,dt,dd,code,samp",' . "\r\n " . 
			'content_css : "../' . $templateDir . '/css/style.css?noc=' . time() . ', ../' . $templateDir . '/css/fonts.css?noc=' . time() .', ../' . $templateDir . '/css/styleTiny.css?noc=' . time() . ', ../' . $templateDir . '/css/fonts.css?noc=' . time() . $addStyle .'",' . "\r\n" .
			'theme_advanced_toolbar_location : "top",' . "\r\n" .
			'theme_advanced_statusbar_location : "bottom", ' . "\r\n" .
			'theme_advanced_resizing : true,' . "\r\n" .
			'theme_advanced_toolbar_align : "left",' . "\r\n" .
			'file_browser_callback : "myFileBrowser",' . "\r\n" .
			'extended_valid_elements: "audio[autoplay|class|controls|dir<ltr?rtl|id|lang|loop|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|preload|src|style|title],video[autoplay|class|controls|dir<ltr?rtl|id|lang|loop|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|preload|poster|src|style|title|width]",' . "\r\n" . 
			'});' . "\r\n";
	
	
		$tiny .= 'function myFileBrowser(field_name, url, type, win){' . 
		'var browserURL = "index.php?c=browser&type="+ type;' .
			'tinyMCE.activeEditor.windowManager.open({' .
			'file : browserURL,' .
			'title : "Przeglądarka plików",' .
			'width : 945,' .
			'height : 500,' .
			'resizable : "yes",' .
			'inline : "yes",' .
			'close_previous : "no"' .
		'}, {' . 
			'window : win,' .
			'input : field_name' .
		'});' . 
		'return false;' .
		'}';
	
		$tiny .= '</script>';
	}
   
    echo $tiny;

}

function checkFileExists($uploadPath)
{
    if (file_exists($uploadPath))
    {
		$filename = substr($uploadPath, 0, strrpos($uploadPath, '.'));
		$uploadPath = $filename . ' - Kopia.' . getExt($uploadPath);
		return checkFileExists($uploadPath);
    } 
	else
    {
		return $uploadPath;
    }
}

function addTip($module, $tip)
{
    global $help;
    $txt = '<a href="#" title="' . $help[$module][$tip] . '" class="helpTip">'
	  .'<img src="template/images/icoTip.png" alt="Podpowiedź" />'
	  .'</a>';
    return $txt;
}

?>