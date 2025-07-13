<?php
	include_once ( CMS_TEMPL . DS . 'header.php');		

	include_once ( CMS_TEMPL . DS . 'top.php');
	
	?>
	<div id="contentWrapper">
		<?php
	        include_once ( CMS_TEMPL . DS . 'left.php');
		?>
		<div id="content" role="main">
	        <a id="skip_txt" tabindex="-1"></a>

			<div id="crumbpath"><span>Jesteś tutaj:</span> <?php echo show_crumbpath($crumbpath, $crumbpathSep); ?></div>
                     
            <div id="content_txt">
			<?php
                include_once ( $TEMPL_PATH );	
            ?>
            </div>
            
		</div>
        <div class="clear"></div>
	</div>        
	
	<?php

	include_once ( CMS_TEMPL . DS . 'bottom.php');			

	include_once ( CMS_TEMPL . DS . 'footer.php');		
?>