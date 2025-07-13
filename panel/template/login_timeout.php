<div id="signupBox">
<?php
	echo $message;
?>
<h1><?= $pageInfo['name']; ?></h1>

<div id="timeout">
<?php
	echo '<p>Wykryto znaczną ilość nieudanych prób logowania.<br/>Spróbuj ponownie za <strong>'.$_COOKIE["login_timeout"].'minut</strong>.</p>';
?>
</div>

<?php
	include_once( CMS_TEMPL . DS . 'pad.php');
	include_once( CMS_TEMPL . DS . 'copyright.php');
?>

</div>