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
    
    $mini = $_REQUEST['mini'];
    $miniWidth = $_REQUEST['miniWidth'];
    $miniHeight = $_REQUEST['miniHeight'];
    $proportional = $_REQUEST['proportional'];
    $jpgCompression = $_REQUEST['jpgCompression'];
    
    switch($idTable)
    {
	case 'banner':
	    define( 'FILES_DIR', 'files' . DS . 'pl' );
	    break;
	
	case 'files':
	    define( 'FILES_DIR', 'download');
	    break;
	
	case 'photos':
	    define( 'FILES_DIR', 'files' . DS . 'pl' );
	    break;
	
	case 'members':
	    define( 'FILES_DIR', 'files' . DS . 'pl' . DS . 'avatars');
	    break;
	
	default :
	    define( 'FILES_DIR', 'container');
	    break;
    }
    
    $fileName = $_REQUEST['fileName'];
    
    $fileName = trim(basename($fileName));
    
    if ($uploadPath == '')
    {
		$uploadPath = '..' . DS . FILES_DIR;
    } 
	else
    {
		$uploadPath = '..' . DS . FILES_DIR . DS . $uploadPath;
    }    
    
    $uploadPath = $uploadPath . DS . $fileName;
    
    $uploadPath = checkFileExists($uploadPath);
    
    $fileName = basename($uploadPath);
    
    echo "fileName=" . urlencode($fileName);
    
    $rawData = file_get_contents("php://input");
    
    file_put_contents($uploadPath , $rawData);
    
    if ($idTable == 'photos' || $idTable == 'files')
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
	
	if ($idTable == 'photos')
	{
	    $params = array(
		'id_page' => $idPage,
		'type' => $idType,
		'pos' => $maxPos,
		'name' => '',
		'file' => $fileName,
		'active' => 1,
		'keywords' => '',
		'data' => date("Y-m-d")
	    );
	}
	
	if ($idTable == 'files')
	{
	    $params = array(
		'id_page' => $idPage,
		'type' => $idType,
		'pos' => $maxPos,
		'name' => $fileName,
		'file' => $fileName,
		'active' => 1,
		'keywords' => '',
		'data' => date("Y-m-d")
	    );	    
	}
	
	$res -> bind_execute($params, $sql);
    }

    
    if ($mini == 1)
    {
		$uploadPath = '..' . DS . FILES_DIR;
		
		if ($proportional == 1)
		{
			imagemax($fileName, $miniWidth, $miniHeight, $uploadPath . DS . 'mini', $uploadPath, $jpgCompression);
		} 
		else
		{
		   imagemini($fileName, $miniWidth, $miniHeight, $uploadPath . DS . 'mini', $uploadPath, $jpgCompression);
		}
    }    
    
}

?>
