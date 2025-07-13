<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<? echo $lang; ?>" xml:lang="<? echo $lang; ?>">
<head>
<title><? echo $pageTitle; ?></title>
<meta name="description" content="<? echo $pageDescription; ?>" />
<meta name="keywords" content="<? echo $pageKeywords; ?>" />
<meta name="author" content="Polska Akademia Dostepnosci - PAD" />
<meta name="revisit-after" content="3 days" />
<meta name="robots" content="all" />
<meta name="robots" content="index, follow" />
<meta http-equiv="Content-Type" content="text/html; charset=<? echo $cmsConfig['charset']; ?>" />
<?
	$pathTemplate = 'http://' . $pageInfo['host'] . '/' . $templateDir . '/mobile';
	
	foreach ($js as $k => $v)
	{
	    echo '<script type="text/javascript" src="'. $pathTemplate .'/../js/' . $v . '"></script>' . "\r\n";
	}
	
	
	foreach ($css as $k => $v)
	{
	    if ($v != 'style.css')
	    {
		echo '<link rel="stylesheet" media="all" type="text/css" href="'. $pathTemplate .'/../css/' . $v . '"/>' . "\r\n";
	    }
	}
	echo '<link rel="stylesheet" media="all" type="text/css" href="'. $pathTemplate .'/css/style.css"/>' . "\r\n";
	
	echo '<link rel="shortcut icon" href="http://' . $pageInfo['host'] . '/' . $templateDir . '/../images/favicon.ico" />' . "\r\n";

?>
</head>
<body>