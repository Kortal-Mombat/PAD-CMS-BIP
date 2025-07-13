<?
	include_once ( CMS_TEMPL . DS . 'header_print.php');	
?>
	<script type="text/javascript">
	    print();
    </script>    
    
    <div id="content">
        <div id="content_txt">
        <h1><?php echo word_wrap( array(' im', ' w'), $pageInfo['name']); ?></h1>
		<?
            include_once ( $TEMPL_PATH );	
        ?>
        </div>
    </div>
    <div class="clear"></div>
<?	
	include_once ( CMS_TEMPL . DS . 'footer_print.php');		
?>