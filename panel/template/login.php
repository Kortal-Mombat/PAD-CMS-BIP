<script type="text/javascript">
$(document).ready(function(){
	$("#form_user").focus();
});
</script>

<div id="signupBox">
   	<?php
		echo $message;
	?>
    <h1><?php echo $pageInfo['name']; ?></h1>
   
    <form action="?action=login" method="post" name="formLogin" id="formLogin">
        <fieldset>
            <legend>Zaloguj się</legend>
            
            <label for="form_user"><?php echo $TXT_user; ?> :</label><br/> 
            <input type="text" name="form_user" id="form_user" aria-required="true" /><br/>
            
            <label for="form_pass"><?php echo $TXT_passwd; ?> :</label><br/>
            <input type="password" name="form_pass" id="form_pass" aria-required="true" /><br/>
            
            <div class="ButLoginWrapper">
	            <input type="submit" name="zaloguj" value="<?php echo $TXT_but_login; ?>" class="butLogin"/>
				<p><a href="index.php?c=password_reset" class="button2"><?php echo $TXT_url_forgot; ?></a></p>
            </div>
        </fieldset>
    </form>
    
   <?php
   	include_once( CMS_TEMPL . DS . 'pad.php');
   	include_once( CMS_TEMPL . DS . 'copyright.php');
   ?>
    
</div>
