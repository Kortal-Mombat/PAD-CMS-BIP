<h2 class="mainHeader"><? echo $pageName; ?></h2>

<?
	echo $message;
	
	echo '<div class="searchList">';
	
	for ($i=$searchStart; $i<($searchStart+$pageConfig['limit']); $i++)
	{
		echo '<div class="searchTxt">'
			.'<h3>'.$searchArray[$i]['url'].'</h3>'
			.'<div class="searchLeadTxt">'.$searchArray[$i]['lead'].'</div>'
			.'</div>';
	}
	
	echo '</div>';
	
	$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;kword=' . $_GET['kword'] . '&amp;s=';
	include (CMS_TEMPL . DS . 'pagination.php');		
?>