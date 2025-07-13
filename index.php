<?php

ini_set('url_rewriter.tags', '');
ini_set('session.use_trans_sid', false);

// error_reporting(E_ALL);

session_start();

header('Content-Type: text/html; charset=utf-8');

/**
 * Zdefiniowanie stalych
 */
define('DS', DIRECTORY_SEPARATOR);
define('CMS_BASE', dirname(__FILE__));
define('CMS_ROOT', dirname(__FILE__));
define('FILES_DIR', 'container');

/**
 * Dolaczenie plików konfiguracyjnych 
 */
include_once ( CMS_BASE . DS . 'includes' . DS . 'load.php');

check_install_files();

/**
 * kontrolery
 */
include_once ( CMS_BASE . DS . 'settings.php');
include_once ( CMS_BASE . DS . 'banertop.php');
include_once ( CMS_BASE . DS . 'menu.php');

/**
 * spr czy strona jest zablokowana 
 */
if ( $outSettings['activeWww'] == 'tak')
{
	include_once( CMS_TEMPL . DS . 'block.php');
}
else
{
	/**
	 * powiekszanie czcionki
	 */
	switch ($_SESSION['style']) {
		case 'r1' :
			setCSS('style_r1.css', $css);
			break;
		case 'r2' :
			setCSS('style_r2.css', $css);
			break;
	}
	
	
	switch ($_GET['c']) {
	
		case 'mobile' :
			if ($_GET['action'] == 'off') {
				unset($_SESSION['mobileVersion'], $mobileVersion);
			} else {
				$_SESSION['mobileVersion'] = 1;
			}
			header("Location: index.php");
			break;
			
		case 'page' :
			include_once ( CMS_BASE . DS . 'page.php');
			break;
	
		case 'error' :
			include_once ( CMS_BASE . DS . 'error.php');
			break;
	
		case 'sitemap' :
			include_once ( CMS_BASE . DS . 'sitemap.php');
			break;
	
		case 'article' :
			include_once ( CMS_BASE . DS . 'article.php');
			break;
	
		case 'getfile' :
			include_once ( CMS_BASE . DS . 'getFile.php');
			break;
	
		case 'search' :
			include_once ( CMS_BASE . DS . 'search.php');
			break;
	
		case 'view' :
			switch ($_GET['type']) {
				case 'page': include_once ( CMS_BASE . DS . 'view_page.php');
					break;
				case 'article': include_once ( CMS_BASE . DS . 'view_article.php');
					break;
			}
			break;
	
	
		default :
			include_once ( CMS_BASE . DS . 'start.php');
			break;
	}
	
	if ($_GET['c'] != 'getfile') {
		// szablon do druku
		if ($_GET['print'] == '1') {
			include_once( CMS_TEMPL . DS . 'print.php');
		// szablon do PDF
		} else 	if ($_GET['pdf'] == '1') {
			include_once( CMS_BASE . DS . 'pdf.php');
	
		} else {
			include_once( CMS_TEMPL . DS . 'index.php');
		}
	}
}
?>