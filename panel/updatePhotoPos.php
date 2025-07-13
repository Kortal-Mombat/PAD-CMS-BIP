<?php
if ($showPanel){
	parse_str($_REQUEST['photoPos'], $photoPos);
	$id_page = $_REQUEST['id_page'];
	$id_type = $_REQUEST['id_type'];
	
	$resMod = new resClass;
	
	if (count($photoPos) > 0){
		$sql = 'UPDATE `' . $dbTables['photos'] . '` SET `pos` = CASE `id_photo` ';
		foreach ($photoPos['photoId'] as $key => $value){
			$pos = $key + 1;
			$sql .= 'WHEN ' . $value . ' THEN ' .$pos . ' ';
		}
		$sql .= 'END WHERE `id_page` = ? AND `type` = ?';
		
		$params = array (
				'id_page' => $id_page,
				'type' => $id_type
				);
		$res -> bind_execute($params, $sql);
	}	
}
?>