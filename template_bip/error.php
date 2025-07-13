<?php
	echo '<h2>'.$err.': ' . str_replace("(", "<br/>(", $title) . '</h2>';

	echo '<div class="txtWrapper">';
	echo '<p>'.$txt.'</p>';
	echo '<p><a href="http://'.$pageInfo['host'].'/index.php">'.$url_home.'</a></p>';

	echo '</div>';	
?>