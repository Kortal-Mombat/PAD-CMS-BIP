<script type="text/javascript">
$(document).ready(function(){
	$("#form_email").focus();
});
</script>

<div id="signupBox">
   	<?php
		echo $message;
	?>
    <h1><?php echo $pageInfo['name']; ?></h1>
   
    <form action="?c=password_reset&amp;action=pswd_reset" method="post" name="formLogin" id="formLogin">
        <fieldset>
            <legend>Nie możesz się zalogować?</legend>
			
			<h2>Zresetuj hasło</h2>

			<p>Nie możesz się zalogować? Nie pamiętasz hasła do konta panelu adminstracyjnego?<br/>
			Wpisz adres email, który został wykorzystany podczas utworzenia konta.</p>

            <label for="form_email">* Adres e-mail :</label><br/> 
            <input type="text" name="form_email" id="form_email" aria-required="true"/><br/>
            
            
            <div class="ButLoginWrapper">
	            <input type="submit" name="send_reset" value="Zresetuj hasło" class="butLogin"/>
				<p><a href="index.php" class="button2">Przejdź do logowania</a></p>
            </div>
        </fieldset>
    </form>

	<?php
		include_once( CMS_TEMPL . DS . 'pad.php');
		include_once( CMS_TEMPL . DS . 'copyright.php');
	?>
    
</div>
