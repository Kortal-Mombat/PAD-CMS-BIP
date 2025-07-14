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
	parse_str($_REQUEST['classPos'], $classPos);
	
	$res = new resClass;
	
	if (count($classPos) > 0){
		$sql = "UPDATE `" . $dbTables['timetable'] . "` SET `pos` = CASE `id` ";
		foreach ($classPos['classId'] as $key => $value){
			$pos = $key + 1 + $sql_start;
			$sql .= "WHEN " . $value . " THEN " .$pos . " ";
		}
		$sql .= "END ";		
	}
	$params = array ();

	$res -> bind_execute($params, $sql);

}
?>