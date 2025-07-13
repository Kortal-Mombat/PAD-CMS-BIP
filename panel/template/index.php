<?php
include_once ( CMS_TEMPL . DS . 'header.php');		
	
if ($showPanel)
{
    include_once ( CMS_TEMPL . DS . 'top.php');
    include_once ( CMS_TEMPL . DS . 'left.php');
?>
    <div id="contentWrapper">
    <div id="crumbpath"><span>Jesteś tutaj:</span> <?= show_crumbpath($crumbpath, $crumbpathSep); ?></div>
    <div id="content" role="main">
    <a id="skip_txt"></a>
	<?php
		include_once ( $TEMPL_PATH );	
    ?>
    </div>
    <?php
	    include_once ( CMS_TEMPL . DS . 'pad.php');
	?>
    </div>        
    <?php
   	 include_once ( CMS_TEMPL . DS . 'bottom.php');			
} 
else
{
    include_once ( $TEMPL_PATH );	
}
include_once ( CMS_TEMPL . DS . 'footer.php');		
?>