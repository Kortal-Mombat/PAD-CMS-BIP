<?php
	include_once ( CMS_TEMPL . DS . 'header.php');	
?>

<div id="contentWrapper">
<img src="template/images/logoPADCMS.png" alt="Logo CMS Polskiej Akademii Dostępności" class="logoPAD"/>
<h1>Instalacja PAD CMS - BIP</h1>

<div id="content" role="main">
<?php
	echo $message;
	
	if ($showForm)
	{
	?>
    	<h2>Wymagane informacje</h2>
        <p>Pola oznaczone gwiazdką (*) są wymagane.</p>
        <p>Możesz je później zmienić w panelu administracyjnym.</p>        
        
        <form action="?step=2" method="post">
            <div><label for="login">Nazwa użytkownika *: </label> <input type="text" name="login" id="login" value="<?php echo $_POST['login'];?>" aria-describedby="login_desc" aria-required="true"/> <span id="login_desc" class="desc">Nazwa użytkownika może zawierać wyłącznie znaki alfanumeryczne, podkreślniki, myślniki oraz znaki „@”.</span></div>
            <div><label for="passwd">Hasło *: </label> <input type="password" name="passwd" id="passwd" aria-describedby="pass_desc" aria-required="true"/> <span id="pass_desc" class="desc">Hasło powinno zawierać minimum <?php echo $passLength; ?> znaków </span></div>
            <div><label for="passwd2">Powtórz hasło *: </label> <input type="password" name="passwd2" id="passwd2" aria-required="true" /> </div>                
            <div><label for="pagename">Nazwa strony: </label> <input type="text" name="pagename" id="pagename" value="<?php echo $_POST['pagename'];?>" size="60"/> </div>
            <div><label for="host">Adres www *: </label> <input type="text" name="host" id="host"  value="<?php echo $_POST['host'];?>"  size="60" aria-required="true" aria-describedby="host_desc" /> <span id="host_desc" class="desc">Podaj pełny adres internetowy, pod którym znajduje się strona. Jeśli znajduje się ona w podkatalogu głównej domeny to również należy go dołączyć do adresu np. http://www.twoja-domena.pl/podkatalog/.</span></div>
<div><label for="email">Adres email *: </label> <input type="text" name="email" id="email"  value="<?php echo $_POST['email'];?>"  size="60" aria-required="true" aria-describedby="email_desc" /> <span id="email_desc" class="desc">Podaj adres e-mail, na który będą trafiać wiadomości z formularza kontaktowego. Posłuży on również jako kontakt w przypadku kiedy zapomnisz hasła.</span></div>
         
            <input type="submit" name="send" value="Zainstaluj"/>          
        </form>        
	<?php
	}
?>
<div id="padInfo">
   	<p><img src="template/images/logos.png" alt="Polska Akademia Dostepnosci realizowana przez Ministerstwo Administracji i Cyfryzacji oraz Fundacje Widzialni"/></p>
</div>

<div id="copyright">
	<p><a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Licencja Creative Commons - CC-BY-SA" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br />PAD CMS jest dostępny na <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">licencji <span lang="en">Creative Commons</span> Uznanie autorstwa - Na tych samych warunkach 4.0 Międzynarodowe</a> z wyłączeniem opublikowanych treści.</p>
</div>  

</div>  
</div>  

<?php
	include_once ( CMS_TEMPL . DS . 'footer.php');	
?>      
