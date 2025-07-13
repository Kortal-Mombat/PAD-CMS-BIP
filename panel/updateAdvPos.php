<?php
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
