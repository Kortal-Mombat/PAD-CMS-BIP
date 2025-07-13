<?php
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