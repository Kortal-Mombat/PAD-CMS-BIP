<?php
	$res = new resClass;

	$sql = "SELECT * FROM `" . $dbTables['menu_types'] . "` WHERE (`lang` = ?) AND (active ='1')";
	$params = array ('lang' => $lang);
	$res->bind_execute( $params, $sql);
	$outMenuType = $res->data;

	foreach ($outMenuType as $k) 
	{
		$menuType[$k['menutype']] = $k;
	}	
	
	//debug($menuType);
	
	// funkcja get_menu_tree() jest w functions.php
?>