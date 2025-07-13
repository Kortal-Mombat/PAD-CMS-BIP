<?php
if ($showPanel)
{
    // potrzebne do zapamietania aktualnej pozycji w menu
    if (isset($_GET['mp']) && $_GET['mp'] != ''){
		$_SESSION['mp'] = $_GET['mp'];
    }
    if (isset($_GET['c']) && $_GET['c'] == '')
    {
		$_SESSION['mp'] = 1;
    }
		
    $res = new resClass;
		
    $sql = "SELECT * FROM `" . $dbTables['menu_panel'] . "` WHERE  active = ? AND lang = ?  ORDER BY pos ";
    $params = array ( 
		'active'=>1, 
		'lang'=>$lang
    );
    $res->bind_execute( $params, $sql);
    $numRowsMenu = $res->numRows;	
    $outRowMenu = $res->data;	

}
?>