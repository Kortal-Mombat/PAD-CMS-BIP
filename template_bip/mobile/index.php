<?php
	if (!defined('CMS_TEMPL')) {
		exit();
	}
    include_once ( CMS_TEMPL . DS . 'header.php');		

    include_once ( CMS_TEMPL . DS . 'top.php');
?>

    <div id="contentWrapper">
	    
	<?php
	include_once ( CMS_TEMPL . DS . 'left.php');
	?>
	    
	    
	<script type="text/javascript">
	// <![CDATA[
	    $(document).ready(function() {
		$('.menuSlider').each(function(){
		    $(this).parent().next().children().css('margin-top', '-' + $(this).parent().next().children().height() + 'px');
		});

		$('.menuSlider').toggle(function(){
		    $(this).css('background-position', 'right -78px');
		    $(this).parent().next().children().stop().animate({'margin-top': '0'}, 300, 'easeOutCubic');
		}, function(){
		    $(this).css('background-position', 'right 13px');
		    $(this).parent().next().children().stop().animate({'margin-top': '-' + $(this).parent().next().children().height() + 'px'}, 300, 'easeOutCubic');
		});
	    });
	// ]]>
	</script>
	
	    
	<div id="content">
	    <a name="skip_txt" id="skip_txt"></a>

	    <div id="crumbpath"><span>Jesteś tutaj:</span> <?php echo show_crumbpath($crumbpath, $crumbpathSep); ?></div>
                     
	<div id="content_txt">
	    <?php
				if (isset($TEMPL_PATH)) {
					include_once ( $TEMPL_PATH );	
				}
            ?>
	</div>
            
</div>
<div class="clear"></div>
	
</div>        
<?php
    include_once ( CMS_TEMPL . DS . 'bottom.php');			
    include_once ( CMS_TEMPL . DS . 'footer.php');		
?>