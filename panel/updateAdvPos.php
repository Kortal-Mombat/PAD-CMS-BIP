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
    parse_str($_REQUEST['advPos'], $advPos);
    $place = $_REQUEST['place'];
    
    $res = new resClass;
    
    if (count($advPos) > 0)
    {
	$sql = 'UPDATE `' . $dbTables['adverts'] . '` SET `pos` = CASE `id_adv` ';
	foreach ($advPos['advId'] as $key => $value)
	{
	    $pos = $key + 1;
	    $sql .= 'WHEN ' . $value . ' THEN ' .$pos . ' ';
	}
	$sql .= 'END WHERE `place` = ?';
	
	$params = array (
	    'place' => $place
	);
	$res -> bind_execute($params, $sql);
	//debug($res);
    }
}
?>
