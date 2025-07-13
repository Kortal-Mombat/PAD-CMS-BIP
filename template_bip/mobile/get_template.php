<h2><?= $pageName; ?></h2>

<?php
	foreach ($templateConfig as $k => $v)
	{
		echo '<div><strong>'.$k.'</strong>: '.$v.'</div>';
	}

?>