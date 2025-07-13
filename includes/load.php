<?php
	include_once ( CMS_BASE . DS . 'includes' . DS . 'config.php' );

	if (isset($_SESSION['mobileVersion']) && $_SESSION['mobileVersion'] == 1) {
		define( 'CMS_TEMPL', CMS_BASE .  DS . $templateDir . DS . 'mobile');
	} else {
		define( 'CMS_TEMPL', CMS_BASE .  DS . $templateDir);
	}	
	
	include_once ( CMS_TEMPL . DS . 'config_template.php' );
	include_once ( CMS_BASE . DS . 'includes' . DS . 'functions.php' );
	include_once ( CMS_BASE . DS . 'includes' . DS . 'db.php' );	

	include_once ( CMS_BASE . DS . 'includes' . DS . 'set.php' );		
	include_once ( CMS_BASE . DS . 'includes' . DS . $lang . DS . 'messages.php');
?>