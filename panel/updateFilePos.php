<?php
if ($showPanel){
	parse_str($_REQUEST['filePos'], $filePos);
	$id_page = $_REQUEST['id_page'];
	$id_type = $_REQUEST['id_type'];
	
	$resMod = new resClass;
	
	if (count($filePos) > 0){
		$sql = 'UPDATE `' . $dbTables['files'] . '` SET `pos` = CASE `id_file` ';
		foreach ($filePos['fileId'] as $key => $value){
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