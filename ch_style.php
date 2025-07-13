<?php
	session_start();
	if (isset($_GET['style'])) {
		$_SESSION['style'] = $_GET['style'];
	}
	
	if ( isset($_GET['contr']) )
	{
		$_SESSION['contr'] = $_GET['contr'];
	}
	header('Location: '.($_SERVER["HTTP_REFERER"] ?? '/'));
?>

