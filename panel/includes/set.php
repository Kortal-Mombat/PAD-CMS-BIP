<?php

	foreach ($_COOKIE as $k => $v) {
		$_COOKIE[$k] = conv_vars ($v);
	}
	foreach ($_POST as $k => $v) {
		//$_POST[$k] = conv_vars ($v);
	}
	
	foreach ($_GET as $k => $v) {
		
		if ($k != 'filename' && $k != 'd' && $k != 'query'  && $k != 'ak'){
	
			$_GET[$k] = conv_vars ($v);
			$_GET[$k] = clean ($v);
		}
	}
	foreach ($_REQUEST as $k => $v) {
		//$_REQUEST[$k] = conv_vars ($v);
	} 

	// Tablica zmiennych do sprawdzenia czy sa numeryczne
	$varToClean = array('UID', 'id', 'idf', 's');
	foreach ($varToClean as $k => $v) {
		if ($_GET[$v]) {
			$_GET[$v] = clean_id($_GET[$v]);
		}
	} 		

	if ($_GET['s'] > 0)
	{
		$sql_start = $cmsConfig['limit'] * $_GET['s'] - $cmsConfig['limit'];
	}
	else
	{
		$_GET['s'] = 1;
		$sql_start = 0;
	}
	$sql_limit = $cmsConfig['limit'];
	
	$js = $css = array ();

	$error = 0;
	$message = '';
	
	$crumbpathSep = '<span class="pathSep"> / </span>';
	$crumbpath[] = array ('name' => 'Start', 'url'=>'index.php');
	
	
	if( empty( $_SESSION ) || !isset( $_SESSION['userData'] ) ) 
	{
		$_SESSION['userData'] = 'empty';
	} 

	/**
	 * Ustawienie js i css 
	 */ 	

	setJS('jquery.min.js', $js);
	setJS('jquery-ui.custom.min.js', $js);
	setJS('jquery.ui.datepicker-pl.js', $js);
	setJS('jquery.mousewheel.js', $js);
	setJS('jquery.mjs.nestedSortable.js', $js);
	setJS('jquery.friendurl.js', $js);
	setJS('jquery.countdown.js', $js);	
	setJS('common.js', $js);
	setJS('swfobject.js', $js);

	setCSS('normalize.css', $css);	
	setCSS('fonts.css', $css);
	setCSS('style.css', $css);
	
	$res = new resClass;

	$sql = "SELECT * FROM `" . $dbTables['settings'] . "` WHERE (`lang`= ?) ORDER BY id_set";
	$params = array ('lang' => $lang);
	$res->bind_execute( $params, $sql);
	foreach ($res->data as $k => $v) 
	{
		$outSettings[$v['id_name']] = $v['attrib'];	
	}
	
	$pageInfo['name'] = $outSettings['pagename'];
	$pageInfo['address'] = $outSettings['address'];
	$pageInfo['email'] = $outSettings['email'];
	$pageInfo['host'] = $outSettings['host'];
	
	$fileFilter = '';
	foreach($cmsConfig['upload_files'] as $value)
	{
	    $fileFilter .= '*.' . $value . '; ';
	}
	$fileFilter = substr($fileFilter, 0, -1);
	
	$photoFilter = '';
	foreach($cmsConfig['photos'] as $value)
	{
	    $photoFilter .= '*.' . $value . '; ';
	}
	$photoFilter = substr($photoFilter, 0, -1);
	
	$upload_max_filesize = ini_get('upload_max_filesize');
	
	if (strpos($upload_max_filesize, 'm') !== false || strpos($upload_max_filesize, 'M') !== false)
	{
	    $uploadSufix = ' MB';
	}
	
	$maxUploadSize = intval($upload_max_filesize);
	
	$uploadMaxFilesize = $maxUploadSize . $uploadSufix;
	
	$arrArticlesNumber = array($cmsConfig['limit'], 20, 50, 100, 200, 500, 'wszystkie');
?>