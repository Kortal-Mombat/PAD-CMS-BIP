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

$idTable = $_REQUEST['idTable'];
$mini = $_REQUEST['mini'];
$miniWidth = $_REQUEST['miniWidth'];
$miniHeight = $_REQUEST['miniHeight'];
$proportional = $_REQUEST['proportional'];
$jpgCompression = $_REQUEST['jpgCompresion'];
$bannerTop = $_REQUEST['bannerTop'];
$uploadPermission = $_REQUEST['uploadPermission'];

switch ($idTable){
    case 'files':
	define( 'FILES_DIR', 'download' );
	break;

    case 'photos':
	define( 'FILES_DIR', 'files' . DS . 'pl' );
	break;

    case 'banner':
	define( 'FILES_DIR', 'files' . DS . 'pl' );
	break;

    case 'members':
	define( 'FILES_DIR', 'files' . DS . 'pl' . DS . 'avatars' );
	break;

    default:
	define( 'FILES_DIR', 'container' );
	break;
}

include_once ( CMS_ROOT . DS . 'includes' . DS . 'functions.php' );

if ($uploadPermission){
	
    if ($_REQUEST['currentpath'] == '../files/pl' || $_REQUEST['currentpath'] == '../files/pl/avatars')
    {
	$currentPath = '..' . DS . FILES_DIR;
    } else
    {
	$currentPath = '..' . DS . FILES_DIR . DS . $_REQUEST['currentpath'];
    }

    $file = trim(basename($_REQUEST["filename"]));

    $uploadPath = $currentPath . DS . $file;

    //$uploadPath = checkFileExists($uploadPath);

    echo "success=false&message=".$uploadPath;

    $RAW_POST_DATA = file_get_contents("php://input");

    file_put_contents($uploadPath, $RAW_POST_DATA);

    /*
    **Mini
    */
    echo "success=false&message=pr-".$proportional;
    if ($mini == 1 && ($idTable == "photos" || $idTable == 'banner' || $idTable == 'members'))
    {
	if ($proportional == 1)
	{
	    imagemax($file, $miniWidth, $miniHeight, $currentPath . DS . 'mini', $currentPath, $jpgCompression);
	    echo "success=false&message=pr-".$proportional;
	} else
	{
	    imagemini($file, $miniWidth, $miniHeight, $currentPath . DS . 'mini', $currentPath, $jpgCompression);
	    echo "success=false&message=nopr-".$proportional;
	}
    }	
}
?>