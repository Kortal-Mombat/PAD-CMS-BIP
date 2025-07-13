<?php

	foreach ($_COOKIE as $k => $v) {
		$_COOKIE[$k] = conv_vars ($v);
	}
	foreach ($_POST as $k => $v) {
		$_POST[$k] = conv_vars ($v);
	}
	foreach ($_GET as $k => $v) {
		
		if ($k != 'filename' && $k != 'confirmed_key'){
			$_GET[$k] = conv_vars ($v);
			$_GET[$k] = clean ($v);
		}
	}
	foreach ($_REQUEST as $k => $v) {
		$_REQUEST[$k] = conv_vars ($v);
	} 

	// Tablica zmiennych do sprawdzenia czy sa numeryczne
	$varToClean = array('UID', 'id', 'idf', 's');
	foreach ($varToClean as $k => $v) {
		if ($_GET[$v]) {
			$_GET[$v] = clean_id($_GET[$v]);
		}
	} 		
	
	$js = $css = array ();

	$error = 0;
	$message = '';
	
	$crumbpathSep = '<span class="pathSep"> / </span>';
	$crumbpath[] = array ('name' => 'Start', 'url'=>'index.php');
	
	/**
	 * Ustawienie js i css 
	 */ 	
	setJS('jquery.min.js', $js);
	setJS('jquery.mousewheel.js', $js);
	setJS('jquery.fancybox.js', $js);
	setJS('jquery.easing.js', $js);
	setJS('jquery.dropdown.js', $js);
	setJS('common.js', $js);

	setCSS('fonts.css', $css);
	setCSS('style.css', $css);
	setCSS('jquery.fancybox.css', $css);
	
	/**
	 * Pobranie IP
	 */ 	
	$userIP = get_ip('onlyIP');
	
	
	/**
	 * strony statyczne
	 */ 	
	$staticPage = array (
		'kontakt' => 4,
		'polityka-pryw' => 8,
		'osw-dost' => 2,		
	);
?>