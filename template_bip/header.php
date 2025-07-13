<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
<title><?php echo $pageTitle; ?></title>
<meta name="description" content="<?php echo $pageDescription; ?>" />
<meta name="keywords" content="<?php echo $pageKeywords; ?>" />
<meta name="author" content="<?php echo $cmsConfig['cms']; ?>" />
<meta name="revisit-after" content="3 days" />
<meta name="robots" content="all" />
<meta name="robots" content="index, follow" />
<meta charset="UTF-8" />

<script type="text/javascript">
// <![CDATA[
		var templateDir = '<?php echo $templateDir;?>';
// ]]>
</script>		
<?php

	$pathTemplate = '//' . $pageInfo['host'] . '/' . $templateDir;
	$fbStyle = 'light';
		
	foreach ($js as $k => $v)
	{
		echo '<script type="text/javascript" src="'. $pathTemplate .'/js/' . $v . '?v='.$cms_version.'"></script>' . "\r\n";
	}
	
	foreach ($css as $k => $v)
	{
		echo '<link rel="stylesheet" media="all" type="text/css" href="'. $pathTemplate .'/css/' . $v . '?v='.$cms_version.'"/>' . "\r\n";
		if ($v == 'style.css')
		{
		    $overlayColor = $templateConfig['overColor'];
		    $popupBackground = '#ffffff';
		}		
	}
	
	if (isset($_SESSION['contr']) && $_SESSION['contr'] == 1)
	{
		$fbStyle = 'dark';	 
		$templateDir .= '/contrast';				
		$overlayColor = $templateConfig['overColor-ct'];
	    $popupBackground = '#000000';
	    
	    echo '<link rel="stylesheet" media="all" type="text/css" href="'. $pathTemplate .'/contrast/css/style.css?v='.$cms_version.'""/>' . "\r\n";
	    echo '<link rel="stylesheet" media="all" type="text/css" href="'. $pathTemplate .'/contrast/css/jquery.fancybox.css"/>' . "\r\n";
	}	
		
	echo '<link rel="shortcut icon" href="//' . $pageInfo['host'] . '/' . $templateDir .'/images/favicon.ico" />' . "\r\n";

?>
<script type="text/javascript">
// <![CDATA[
    $(document).ready(function(){  
		var templateDir = '<?php echo $templateDir;?>';
		
		$("a[rel=fancybox]").fancybox({
			overlayOpacity	: 0.8,
			overlayColor	: '<?php echo $overlayColor; ?>',
			titlePosition 	: 'outside',
			titleFormat	: function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-over">Zdjęcie ' + (currentIndex + 1) + ' / ' + currentArray.length + '</span>' + (title.length ? ' &nbsp; ' + title : '') ;
			}
		});	
    });
// ]]>
</script>
</head>
<body>