<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	$php_version = '8.4.0';
	$cms_version = '1.2.1.ak3';
	
	setlocale(LC_ALL, 'pl_PL');
	
	// konfiguracja strony
	$pageConfig = array(
		'limit'    => 5,
		'prohibited' => array("bcc:", "subject", "cc:", "sex", "http", "pussy", "ringtones", "fuck" ,"fukin", "nudes", "viagra", "phentermine", "tramadol", "xanax", "cipcia", "cwel", "cycki", "franca", "gówno", "gównian", "ruchać", "wyruchan", "skurwi", "zajeban", "burdel", "kurw","kurewk","huj","chuj","pierdol","popierdol","jeban","pojeban","cipa","cipy","cipk","dupa","dupki","dupiat","pizda","pieprzy","piepszy","popieprz","kutas","kutafon","dupe","dupę","fiut","zajebiscie","zajebiście","zajebist","jebać", "dupczy", "jebań", "cwel", "pizdziocha", "pizdy", "pizdę", "pizde"),
		'popupInterval' => 1800 //pół godziny
	);

	// konfiguracja cms
	$cmsConfig = array(
		'charset' => 'utf-8',
		'default_lang' => 'pl',
		'cms_title' => 'PAD Panel BIP',
		'cms' => 'PAD',
		'help_email' => 'pad@widzialni.org',
		'limit' => 10,
		'upload_files' => array('txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'odt', 'odf', 'odp', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'ppt', 'pptx', 'pps', 'avi', 'mov', 'mpg', 'mpeg', 'flv', 'mp3', 'mp4', 'wav', 'zip', 'rar', 'tar', '7z', 'ogg', 'ogv', 'wmv', 'rm', 'm4v', 'm2v', '3gp', '3g2'),
		'photos' => array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'GIF'),
		'replace_char' => array(' '=>'-', '%'=>'', '"'=>'', '\''=>'', '\\'=>'', '_'=>'-', ','=>'-', '/'=>'-', '&'=>'', 'ą'=>'a', 'ż'=>'z', 'ś'=>'s', 'ź'=>'z', 'ę'=>'e', 'ć'=>'c', 'ń'=>'n', 'ó'=>'o', 'ł'=>'l', 'Ą'=>'A', 'Ż'=>'Z', 'Ś'=>'S', 'Ź'=>'Z', 'Ę'=>'E', 'Ć'=>'C', 'Ń'=>'N', 'Ó'=>'O', 'Ł'=>'L', '&quot;'=>''),
		'replace_char_meta' => array('"'=>'', '\''=>'', '\\'=>'', ';'=>',', ':'=>'-', '{'=>'(', '}'=>')'),
		'replace_char_toview' => array('"'=>'[cudzyslow]', '\''=>'[apostrof]', ';'=>'[srednik]', ':'=>'[dwukropek]', '{'=>'[klamral]', '}'=>'[klamrap]'),
		'replace_char_fromview' => array('[cudzyslow]'=>'"', '[apostrof]'=>'\'', '[srednik]'=> ';', '[dwukropek]'=>':', '[klamral]'=>'{', '[klamrap]'=>'}')
	);

	// dostepne tebele
	$dbTables = array(
		'users' => $dbConfig['prefix'] . 'users',
		'pages' => $dbConfig['prefix'] . 'pages',
		'monitor' => $dbConfig['prefix'] . 'monitor',
		'menu_types' => $dbConfig['prefix'] . 'menu_types',
		'menu_panel' => $dbConfig['prefix'] . 'menu_panel',
		'articles' => $dbConfig['prefix'] . 'articles',
		'art_to_pages' => $dbConfig['prefix'] . 'art_to_pages',
		'files' => $dbConfig['prefix'] . 'files',
		'photos' => $dbConfig['prefix'] . 'photos',
		'settings' => $dbConfig['prefix'] . 'settings',
		'priv' =>  $dbConfig['prefix'] . 'privileges',
		'register' =>  $dbConfig['prefix'] . 'register',  
		'counter' =>  $dbConfig['prefix'] . 'counter',
		'viewer' =>  $dbConfig['prefix'] . 'viewer',  	
	);
 	
	// typy userow
	$users_type = array (
		'admin' => 'administrator', 
		'user' => 'użytkownik',
	);	
	
	$arrSchoolWeek = array (
		0=>array('short'=>'pon', 'long'=>'Poniedziałek'),
		1=>array('short'=>'wt', 'long'=>'Wtorek'),
		2=>array('short'=>'sr', 'long'=>'Środa'),
		3=>array('short'=>'czw', 'long'=>'Czwartek'),
		4=>array('short'=>'pt', 'long'=>'Piątek')
	);	
	
	$arrWeek = array (
		0=>array('short'=>'pn', 'long'=>'Poniedziałek'),
		1=>array('short'=>'wt', 'long'=>'Wtorek'),
		2=>array('short'=>'sr', 'long'=>'Środa'),
		3=>array('short'=>'cz', 'long'=>'Czwartek'),
		4=>array('short'=>'pt', 'long'=>'Piątek'),
		5=>array('short'=>'so', 'long'=>'Sobota'),	
		6=>array('short'=>'nd', 'long'=>'Niedziela')				
	);	
	
	$arrMonth  = array ('Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień');
	$calendarMonth  = array (1 => 'Stycznia', 'Lutego', 'Marca', 'Kwietnia', 'Maja', 'Czerwca', 'Lipca', 'Sierpnia', 'Września', 'Października', 'Listopada', 'Grudnia');
			
	// lista wojewodztw
	$arrWoj = array (1=>'dolnośląskie','kujawsko-pomorskie','lubelskie','lubuskie','łódzkie','małopolskie','mazowieckie','opolskie','podkarpackie','podlaskie','pomorskie','śląskie','świętokrzyskie','warmińsko-mazurskie','wielkopolskie','zachodniopomorskie');
	
	$arrWybor = array ('tak','nie');
	$arrOnOff = array ('on','off');
 
	$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
	$PHP_SELF = $_SERVER['PHP_SELF'];
	
	$date = date("Y-m-d H:i:s");
	$shortDate = date("Y-m-d");
			
	if (isset($_SESSION['lang'])) {
		$lang = $_SESSION['lang'];
	} else {
		$lang = 'pl';
	}
	
	// wymagana dlugosc hasla
	$passLength = 8;			
  
  $leftAdv = $topAdv = array();  
?>