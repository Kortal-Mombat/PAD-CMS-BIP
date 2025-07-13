<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<? echo $lang; ?>" xml:lang="<? echo $lang; ?>">
<head>
<title><? echo $pageTitle; ?></title>
<meta name="description" content="<?php echo $pageDescription; ?>" />
<meta name="keywords" content="<?php echo $pageKeywords; ?>" />
<meta name="author" content="<?php echo $cmsConfig['cms']; ?>" />
<meta name="revisit-after" content="3 days" />
<meta name="robots" content="all" />
<meta name="robots" content="index, follow" />
<meta charset="UTF-8" />

<?php
	$pathTemplate = 'http://' . $pageInfo['host'] . '/' . $templateDir;
	
	foreach ($js as $k => $v)
	{
		echo '<script type="text/javascript" src="'. $pathTemplate .'/js/' . $v . '"></script>' . "\r\n";
	}
	
	foreach ($css as $k => $v)
	{
		echo '<link rel="stylesheet" media="all" type="text/css" href="'. $pathTemplate .'/css/' . $v . '"/>' . "\r\n";
	}
	echo '<link rel="stylesheet" media="all" type="text/css" href="'. $pathTemplate .'/css/print.css"/>' . "\r\n";	
	echo '<link rel="shortcut icon" href="http://' . $pageInfo['host'] . '/' . $templateDir .'/images/favicon.ico" />' . "\r\n";
?>
</head>
<body>