<?php
if ($showPanel)
{
	$TEMPL_PATH = CMS_TEMPL . DS . 'start.php';
	$pageTitle = ($pageTitle ?? ''). $TXT_welcome;
	
	// usuniecie podgladow
	$sql = "TRUNCATE TABLE `" . $dbTables['viewer'] . "`";
	$params = array ();
	$res->bind_execute( $params, $sql);
	$numRows = $res->numRows;	
	
	// Licznik
	$sql = "SELECT * FROM `".$dbTables['counter']."` WHERE id='1' LIMIT 1";
	$params = array ();
	$res->bind_execute( $params, $sql);
	$counter = $res->data[0]['count'];	
	
	// Licznik Page		
	$sql = "SELECT * FROM `" . $dbTables['pages'] . "` ORDER BY counter DESC LIMIT 1";
	$params = array ();
	$res->bind_execute( $params, $sql);
	$countPage = $res->data[0];	

	
	// Licznik Article
	$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
			FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
			ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
			ORDER BY counter DESC
			LIMIT 1";
	$params = array ();
	$res->bind_execute( $params, $sql);
	$countArticle = $res->data[0];	
}
?>