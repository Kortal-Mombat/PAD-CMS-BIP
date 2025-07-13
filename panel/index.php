<?php
	ini_set('url_rewriter.tags', '');
	ini_set('session.use_trans_sid', false); 
	
	// error_reporting(E_ALL);

	session_start();
	
	/**
	 * Zdefiniowanie stalych
	 */ 	
	define( 'DS', DIRECTORY_SEPARATOR );
	define( 'CMS_BASE', dirname(__FILE__) );

	$parts = explode( DS, CMS_BASE );
	array_pop( $parts );
	define( 'CMS_ROOT', implode( DS, $parts ) );
	define( 'CMS_TEMPL', CMS_BASE .  DS . 'template');
	
	define( 'FILES_DIR', 'container');
	
	/**
	 * Dolaczenie plików konfiguracyjnych 
	 */ 
	include_once ( CMS_BASE . DS . 'includes' . DS . 'load.php');

	/**
	 * Sprawdzenie czy zalogowany 
	 */ 
	$showPanel = false;
	if (check_login_user())
	{
		// po zalogowaniu beda dolaczone pozostale pliki do szablonu: top, left, bottom, etc
		$showPanel = true;	
		include_once ( CMS_BASE . DS . 'left.php');	
	}
	else
	{
		setCSS('login.css', $css);
		$pageTitle = "Logowanie";			
	}
			 	
	include_once ( CMS_BASE . DS . 'login.php');
	
 	/**
	 * wersja TinyMCE
	 */ 	
	$res = new resClass;
	$sql = "SELECT * FROM `" . $dbTables['settings'] . "` WHERE (`id_name`= 'editor') LIMIT 1";
	$params = array ();
	$res->bind_execute( $params, $sql);
	$tinyVersion = $res->data[0]['attrib'];			

 	/**
	 * kontrolery
	 */ 		
	switch($_GET['c']) {
			
		case 'page' : 
			include_once ( CMS_BASE . DS . 'page.php');					break;
			
		case 'logout' : 
			include_once ( CMS_BASE . DS . 'logout.php');				break;

		case 'help' : 
			include_once ( CMS_BASE . DS . 'help.php');					break;
			
		case 'monitor' : 
			include_once ( CMS_BASE . DS . 'monitor.php');				break;

		case 'users' : 
			include_once ( CMS_BASE . DS . 'users.php');				break;
			
		case 'priv' : 
			include_once ( CMS_BASE . DS . 'privileges.php');			break;
						
		case 'files' : 
			include_once ( CMS_BASE . DS . 'files.php');				break;
									
		case 'photos' : 
			include_once ( CMS_BASE . DS . 'photos.php');				break;
												
		case 'articles' : 
			include_once ( CMS_BASE . DS . 'articles.php');				break;
			
		case 'settings' : 
			include_once ( CMS_BASE . DS . 'settings.php');				break;
				
		case 'explorer' : 
			include_once ( CMS_BASE . DS . 'explorer.php');				break;

		case 'updatePhotoPos' :
			include_once ( CMS_BASE . DS . 'updatePhotoPos.php');		break;

		case 'updateFilePos' :
			include_once ( CMS_BASE . DS . 'updateFilePos.php');		break;

		case 'updateArtPos' :
			include_once ( CMS_BASE . DS . 'updateArtPos.php');			break;	
			
		case 'updateGroupPos' :
			include_once ( CMS_BASE . DS . 'updateGroupPos.php');		break;

		case 'register' :
			include_once ( CMS_BASE . DS . 'register.php');				break;
			
		case 'browser' :
			include_once ( CMS_BASE . DS . 'browser.php');				break;
		
		case 'search' :
			include_once ( CMS_BASE . DS . 'search.php' );				break;
		    
		case 'password_reset' :
			include_once ( CMS_BASE . DS . 'password_reset.php' );		break;
		    
		default : 
			include_once ( CMS_BASE . DS . 'start.php');				break;
			
	}	
		
	if ($_GET['c'] != 'files' && $_GET['c'] != 'photos' && $_GET['c'] != 'updatePhotoPos' && $_GET['c'] != 'updateFilePos' && $_GET['c'] != 'updateGroupPos' && $_GET['c'] != 'updateArtPos' && $_GET['c'] != 'browser' )
	{
		include_once( CMS_TEMPL . DS . 'index.php');
	}

	if ($_GET['c'] == 'browser'){
		include_once( CMS_TEMPL . DS . 'browser.php');
	}
	
	//debug ($_SESSION['userData']);	 
	
?>