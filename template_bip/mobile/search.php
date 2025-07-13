<h2 class="mainHeader"><?= $pageName; ?></h2>

<?php
	echo $message;
	
	echo '<div class="searchList">';
	
	for ($i=($searchStart ?? 0); $i<(($searchStart ?? 0)+$pageConfig['limit']); $i++)
	{
		echo '<div class="searchTxt">'
			.'<h3>'.($searchArray[$i]['url'] ?? '').'</h3>'
			.'<div class="searchLeadTxt">'.($searchArray[$i]['lead'] ?? '').'</div>'
			.'</div>';
	}
	
	echo '</div>';
	
	$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;kword=' . $_GET['kword'] . '&amp;s=';
	include (CMS_TEMPL . DS . 'pagination.php');		
?>