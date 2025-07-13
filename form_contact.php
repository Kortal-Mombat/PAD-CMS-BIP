<h3 class="header">Formularz kontaktowy</h3>
<a name="form"></a>
<?
	$GET_captcha = intval($_POST["captcha"]);
	$captcha_txt = '';
	$ile_liczb = mt_rand(2, 3);
	$captcha_wynik = 0;

	$ip = $_SERVER["REMOTE_ADDR"]; 
	
	if ($_GET['action'] == 'send')
	{
		$imie = 'value="'.$_POST['imie'].'"';
		$text = htmlspecialchars(strip_tags($_POST['text']));
		$email = 'value="'.$_POST['email'].'"';
		$tel = 'value="'.$_POST['tel'].'"';
						
		// dzban miodu
		if ($_POST['dm'] == '')
		{
			if($GET_captcha != $_SESSION["captcha_wynik"])
			{ 
				$err .= '<li>'.$ERR['form_captcha'].'</li>';
			}
			
			if (trim($_POST['imie'])=='')
			{
				$err .= '<li>'.$ERR['form_name'].'</li>';		
			}
			if (trim($_POST['text'])=='')
			{
				$err .= '<li>'.$ERR['form_text'].'</li>';		
			}	
			if (check_email_addr($_POST['email']) == 0)
			{
				$err .= '<li>'.$ERR['require_email'].'</li>';		
			}
			for ($i=0;$i<=(count($pageConfig['prohibited'])-1);$i++)
			{
				if (preg_match ("/".$pageConfig['prohibited'][$i]."/i", $_POST['imie']) || preg_match ("/".$pageConfig['prohibited'][$i]."/i", $_POST['email']) || preg_match ("/".$pageConfig['prohibited'][$i]."/i", $_POST['tel']) || preg_match ("/".$pageConfig['prohibited'][$i]."/i", $_POST['text']))
				$sl_err = '<li>'.$ERR['form_prohibited'].'</li>';		
			}
			
			if ($err || $sl_err)
			{
				echo '<a name="form"></a>'
					.'<div class="txt_err"><h4>' . $ERR['form_err'] . '</h4>'
						.'<ul>'
							. $err . $sl_err
						.'</ul>'
					.'</div>';					
			}
			else
			{
				$to  = $pageInfo['email'];
				$subject = $TXT['contact_subject'] . ' - ' .$pageInfo['host'];					

				$content  = "<h1>" . $subject . "</h1>";
				$content .= "<p><b>Imię i nazwisko:</b> ".$_POST['imie']."</p>";
				$content .= "<p><b>Telefon:</b> ".$_POST['tel']."</p>";
				$content .= "<p><b>E-mail:</b> ".$_POST['email']."</p>";
				$content .= "<p><b>Treść:</b><br/>".$text."<p>\r\n";
	
				$content .= "<p><b>IP nadawcy:</b> ".get_ip()."</p>\r\n";
				
				$sm = send_mail( $to, $subject, $content);

				if ($sm) 
				{
					echo '<div class="txt_msg">' . $TXT['contact_msg'] .'</div>';	
				}	
				else
				{
					echo '<div class="txt_err">' . $TXT['contact_err'] . '<a href="mailto:'.$pageInfo['email'].'">' .$pageInfo['email'] . '</a></div>';					
				}
			}
		}
		else
		{
			echo '<div class="txt_err">Nie spamuj.</div>';			
		}
	}
	else
	{
		$imie = 'value=""';
		$text = '';
		$email = 'value=""';
		$tel = 'value=""';		
	}
	

	for ($i=1; $i<=$ile_liczb; $i++)
	{
		$ar_liczby[$i] = mt_rand(1, 10);
		$captcha_txt .= $ar_liczby[$i] . '+';
		$captcha_wynik = $captcha_wynik + $ar_liczby[$i];
	}
	$_SESSION["captcha_wynik"] = $captcha_wynik;
	
	$captcha_txt = substr($captcha_txt, 0, -1);	

	
?>
    <form name="f_contact" class="f_contact" method="post" action="<? echo 'index.php?c='.$_GET['c'].'&amp;id='.$_GET['id'].'&amp;action=send#form'; ?>">
    <input type="hidden" name="dm" value="" />
                
	<fieldset>  
		<legend class="hide">Kontakt</legend>

        <label for="name">Imię i nazwisko (wymagane):</label><br />
        <div class="inputborder"><input type="text" id="name" name="imie" size="60" maxlength="40" <? echo $imie; ?> /></div>

        <label for="email">Adres e-mail (wymagane):</label><br /> 
        <div class="inputborder"><input type="text" id="email" name="email" size="60" maxlength="40" <? echo $email; ?> /></div>

        <label for="tel">Numer telefonu:</label><br /> 
        <div class="inputborder"><input type="text" id="tel" name="tel" size="60" maxlength="40" <? echo $tel; ?> /></div>

        <label for="text">Treść pytania (wymagane):</label><br />
        <div class="inputborder"><textarea id="text" name="text" cols="57" rows="8"><? echo $text; ?></textarea></div>
                    
        <p class="kom">Twój numer IP: <strong><? echo get_ip(); ?></strong></p>		

        <p>W celu utrudnienia rozsyłania spamu przez automaty, proszę rozwiązać proste zadanie matematyczne.<br />Dla przykładu: 2+1 daje 3.</p>
        <label for="captcha">* Zadanie matematyczne: <? echo $captcha_txt; ?> daje </label> <input type="text" id="captcha" title="Tutaj wpisz rozwiązanie" name="captcha" size="3" maxlength="10" />
        <br />			

		<?
        if ($_POST['zgoda']=='on')
      		$checked = 'checked';
        else
       		$checked = '';
        ?>
        <div class="inputAgree">
            <input type="checkbox" name="zgoda" id="zgoda" value="on" <? echo $checked; ?> />
            <label for="zgoda">Wyrażam zgodę na przetwarzanie moich danych osobowych przez <?php echo $pageInfo['name']; ?>, zgodnie z obowiązującym prawem i polityką prywatności.</label>
        </div>
          
		<div class="butWarapper">
       	 <input type="submit" name="ok" id="sendForm"  value="Wyślij formularz" class="button"/>
        </div>

	</fieldset>
	</form>
