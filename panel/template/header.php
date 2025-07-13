<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<title><?= ($pageTitle ?? '') . ' - ' . $cmsConfig['cms_title'] . ' - ' . $pageInfo['name']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Polska Akademia Dostępności" />
<meta name="robots" content="noindex, nofollow" />
<?php
	foreach ($js as $k => $v)
	{
		echo '<script type="text/javascript" src="//' . $pageInfo['host'] . '/panel/template/js/' . $v . '"></script>' . "\r\n";
	}
	
	foreach ($css as $k => $v)
	{
		echo '<link rel="stylesheet" media="all" type="text/css" href="//' . $pageInfo['host'] . '/panel/template/css/' . $v . '"/>' . "\r\n";
	}
	
	echo '<link rel="shortcut icon" href="//' . $pageInfo['host'] . '/panel/template/images/favicon.ico" />' . "\r\n";
	
	$_GET['c'] = $_GET['c'] ?? '';
	/*
	// do browsera
	*/
	if ($_GET['c'] == 'browser'){
	?>
	<script type="text/javascript" src="//<?php echo $pageInfo['host']; ?>/panel/template/js/tiny_mce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript">
	var FileBrowserDialogue = {
		init : function () {
		},
		mySubmit : function (file) {
			
			var win = tinyMCEPopup.getWindowArg("window");
	
			win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = file;
	
			if (typeof(win.ImageDialog) != "undefined"){
				if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
				if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(file);
			}
	
			tinyMCEPopup.close();
		}
	}
	tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);	
	</script>
	<?php
	}

?>
</head>
<body>