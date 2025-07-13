<h2><? echo $pageName; ?></h2>

<?
	foreach ($templateConfig as $k => $v)
	{
		echo '<div><strong>'.$k.'</strong>: '.$v.'</div>';
	}

?>