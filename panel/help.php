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
	$TEMPL_PATH = CMS_TEMPL . DS . 'help.php';
	$pageTitle .= $TXT_but_help;
		
	setCSS('jquery.ui.theme.css', $css);
	setCSS('jquery.ui.tabs.css', $css);
				
	$crumbpath[] = array ('name' => $TXT_but_help, 'url' => $PHP_SELF . '?c=' . $_GET['c']);
	
	/**
	 * wyslanie wiadomosci
	 */
	if ($_GET['action'] == 'send')
	{
		$subject =  conv_vars($_POST['subject']);
		$text =  conv_vars($_POST['text']);	

		if ($subject == '')
		{
			$message .= show_msg ('err', $ERR_email_subject);
		}
		else if ($text == '') 
		{
			$message .= show_msg ('err', $ERR_email_text);
		}
		else
		{
			$mail_body = $text 
						.'<hr/>'
						. $TXT_mail_footer;
									
			send_mail( $cmsConfig['help_email'], $TXT_but_help . ' - ' . $subject, $mail_body );	
			
			$subject = $text = '';		
		}
	}
}
?>