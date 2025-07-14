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
if ($showPanel)
{
    if (get_priv_controler($_GET['c']))
    {
	
	// zamiana na encje dla wybranych zmiennych
	$specialCharsNames = array ('name');
	foreach ($_POST as $k => $v)
	{
	    if (in_array($k, $specialCharsNames))
	    {
			$_POST[$k] = htmlspecialchars(strip_tags($v), ENT_QUOTES);		
	    }
	}
	
	$TEMPL_PATH = CMS_TEMPL . DS . 'welcome.php';
	
	$res = new resClass;
	
	$pageTitle = 'Tekst powitalny na stronie głównej';
	
	$crumbpath[] = array ('name' => $pageTitle, 'url' => $PHP_SELF . '?c=welcome');
	
	/*
	 * Aktualizacja tablicy
	 */
	if ($_GET['action'] == 'update')
	{
	    $sql = "update `" . $dbTables['board'] . "` set `text`= ? , `active`= ?, `modified_date` = ? where (`id` = ?)";

	    $params = array(
		'text'		    => $_POST['text'],
		'active'	    => $_POST['active'],
		'modified_date'	    => date("Y-m-d H:i:s"),
		'id'		    => $_POST['id']
	    );
	    $res->bind_execute($params, $sql);
	    
	    $message .= show_msg ('msg', 'Tekst powitalny na stronie głównej został zaktualizowany');
	    monitor( $_SESSION['userData']['UID'], 'Aktualizacja tekstu powitalnego na stronie głównej', get_ip() );
	}
	
	/*
	 * Pobranie tablicy
	 */
	$sql = "select * from `" . $dbTables['board'] . "` where (id='2') AND (`lang`='" . $lang . "') ";
	
	$params = array();
	$res->bind_execute($params, $sql);
	$r = $res->data[0];
	
	$checked = '';
	if ($r['active'] == 'on')
	{
	    $checked = 'checked="checked"';
	}
	
    } else
    {
	$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
	$message .= show_msg ('err', $ERR_priv_access);	
    }
    
} else
{
    header ('Location: ../index.php');
}
?>
