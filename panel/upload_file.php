<?php

$uploadPermission = $_REQUEST['uploadPermission'];

if ($uploadPermission == 1)
{
    define( 'DS', DIRECTORY_SEPARATOR );    
    define( 'CMS_BASE', dirname(__FILE__) );

    $parts = explode( DS, CMS_BASE );
    array_pop( $parts );
    define( 'CMS_ROOT', implode( DS, $parts ) ); 
    
    include_once ( CMS_ROOT . DS . 'includes' . DS . 'config.php' );
    include_once ( CMS_ROOT . DS . 'includes' . DS . 'db.php' );
    include_once ( CMS_ROOT . DS . 'includes' . DS . 'functions.php' );
    
    $idTable = $_REQUEST['idTable'];
    $idPage = $_REQUEST['idPage'];
    $idType = $_REQUEST['idType'];
    
    $uploadPath = $_REQUEST['uploadPath'];
    
    if ($idTable == 'files')
    {
	define( 'FILES_DIR', 'download');
    } else
    {
	define( 'FILES_DIR', 'container');
    }
    $fileName = trans_url_name_may($_FILES['Filedata']['name']);
    
    $fileName = trim(basename($fileName));

    if ($uploadPath == '.' || $uploadPath == '')
    {
	$uploadPath = '..' . DS . FILES_DIR;
    } else
    {
	$uploadPath = '..' . DS . FILES_DIR . DS . substr($uploadPath, 2);
    }
    
    $uploadPath = $uploadPath . DS . $fileName;

    $uploadPath = checkFileExists($uploadPath);
    
    $fileName = basename($uploadPath);
    
    move_uploaded_file($_FILES['Filedata']['tmp_name'], $uploadPath);
	
    echo "fileName=" . urlencode($fileName);
    
    if ($idTable == 'files')
    {
	$res = new resClass;

	$sql = "SELECT MAX(pos) AS maxPos FROM `" . $dbTables[$idTable] . "` WHERE (`id_page` = ?) AND (`type` = ?)";
	$params = array(
	    'id_page'	=> $idPage,
	    'type'	=> $idType
	);
	$res -> bind_execute($params, $sql);
	$maxPos = $res->data[0]['maxPos'] + 1;

	$sql = "INSERT INTO `" . $dbTables[$idTable] . "` VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)";
	$params = array(
	    'id_page'	=> $idPage,
	    'type'	=> $idType,
	    'pos'	=> $maxPos,
	    'name'	=> $fileName,
	    'file'	=> $fileName,
	    'active'	=> 1,
	    'keywords'	=> '',
	    'data'	=> date("Y-m-d")
	);
	$res -> bind_execute($params, $sql);	
    }    
	
}
?>