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
	

    <form action="?c=password_reset&amp;action=reset&amp;user=<?php echo $_GET['user']; ?>&amp;ak=<?php echo $_GET['ak']; ?>" method="post" name="formLogin" id="formLogin">
        <fieldset>
            <legend>Zmiana hasła</legend>
			
			<?php 
			if ($showForm) { 
				?>			
				<h2>Wprowadź nowe hasło</h2>

				<label for="passwd">Hasło *: </label><br/>
				<input type="password" name="passwd" id="passwd" aria-describedby="pass_desc" aria-required="true" autocomplete="off"/><br/>
				<div id="pass_desc" class="desc">Hasło powinno zawierać minimum <?php echo $passLength; ?> znaków </div><br/>
				
				<label for="passwd2">Powtórz hasło *: </label><br/>
				<input type="password" name="passwd2" id="passwd2" aria-required="true" autocomplete="off" /><br/>
				
				<div class="ButLoginWrapper">
					<input type="submit" name="send_reset" value="Zapisz nowe hasło" class="butLogin"/>
					<p><a href="index.php" class="button2">Przejdź do logowania</a></p>
				</div>				
				<?php
			}
			else
			{
				?>
				<div class="ButLoginWrapper">
					<p><a href="index.php" class="button2">Przejdź do logowania</a></p>
				</div>				
				<?php
			}
			?>					
        </fieldset>
    </form>
    
	<?php
		include_once( CMS_TEMPL . DS . 'pad.php');
		include_once( CMS_TEMPL . DS . 'copyright.php');
	?>
    
</div>
