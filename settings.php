<?php
	if (!class_exists('resClass')) {
		exit();
	}

	$res = new resClass;
	$sql = "SELECT * FROM `" . $dbTables['settings'] . "` WHERE (`lang`= ?) ORDER BY id_set";
	$params = array ('lang' => $lang);
	$res->bind_execute( $params, $sql);
	foreach ($res->data as $k => $v) 
	{
		$outSettings[$v['id_name']] = $v['attrib'];	
	}
	
	//debug($outSettings);	
	
	if (check_html_text($outSettings['metaTitle'])) {
		$pageTitle = $outSettings['pagename'];
	} else {
		$pageTitle = $outSettings['metaTitle'];
	}
	
	$pageDescription = $outSettings['metaDesc'];
	$pageKeywords = $outSettings['metaKey'];	
	
	$pageInfo['name'] = $outSettings['pagename'];
	$pageInfo['address'] = $outSettings['address'];
	$pageInfo['logo'] = $outSettings['logo'];
	$pageInfo['email'] = $outSettings['email'];	
	$pageInfo['host'] = $outSettings['host'];

    if ($outSettings['www'] != '')
	{
		$replace = ['http://','https://'];
		$pageInfo['www'] = '//' . str_replace($replace, '', $outSettings['www']);
	}
		
	if (is_numeric($outSettings['artNumStart']) )
	{
		$sql_limit = $outSettings['artNumStart'];
		
		// zmiana dumyslnej ilosci art na stronie
		$pageConfig['limit'] =  $outSettings['artNumStart'];
	} 
	else
	{
		$sql_limit = $pageConfig['limit'];
	}
	
	$_GET['s'] = $_GET['s'] ?? 0;
	if ($_GET['s'] > 0)
	{
		$sql_start = $sql_limit * $_GET['s'] - $sql_limit;
	} 
	else
	{
		$_GET['s'] = 1;
		$sql_start = 0;
	}

?>