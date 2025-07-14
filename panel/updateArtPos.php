<?php
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('CMS_BASE')) {
	define( 'CMS_BASE', dirname(__FILE__) );
}
if (!defined('CMS_ROOT')) {
	$parts = explode( DS, CMS_BASE );
	array_pop( $parts );
	define( 'CMS_ROOT', implode( DS, $parts ) ); 
}
include_once ( CMS_ROOT . DS . 'includes' . DS . 'check.php' );
if ($showPanel){
	parse_str($_REQUEST['articlePos'], $articlePos);
	$idPage = $_REQUEST['idPage'];
	$sql_start = $_REQUEST['sql_start'];
	
	$resMod = new resClass;
	
	if (count($articlePos) > 0){
		$sql = 'UPDATE `' . $dbTables['art_to_pages'] . '` SET `pos` = CASE `id_art` ';
		$tempArr = array();
		foreach ($articlePos['artId'] as $key => $value){
			$pos = $key + 1 + $sql_start;
			$sql .= 'WHEN ' . $value . ' THEN ' .$pos . ' ';
			$tempArr[] = $value;
		}
		$sql .= 'END WHERE `id_art` in (' . implode(',' , $tempArr) . ') and `id_page` = ?';		
	}
	$params = array (
		'id_page' => $idPage
	);
	$res -> bind_execute($params, $sql);
	debug($res);
}
?>