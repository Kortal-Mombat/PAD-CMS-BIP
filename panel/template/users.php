<h2><? echo $pageTitle; ?></h2>

<ul class="navMenu">
	<li><a href="<? echo $PHP_SELF.'?c=' . $_GET['c']; ?>"><span class="icoShow"></span>Pokaż wszystkich</a></li>
    <li><a href="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=add'; ?>"><span class="icoAdd"></span>Dodaj użytkownika</a></li>	
</ul>

<div class="clear"></div>

<?
	echo $message;

	if ($showAddForm)
	{
		?>
			<form method="post" class="formEdAdd" action="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=' . $_GET['action'] . '&amp;act=addPoz'; ?>" name="formAdd" enctype="multipart/form-data">

				<h3>Dodaj nowego użytkownika</h3>
				
				<div class="formWrap">
					
					<label for="name">* Imię i nazwisko: </label>
					<input type="text" name="name" id="name" size="100" maxlength="250" value="<? echo $_POST['name']; ?>" aria-required="true"/><br/>
					
					<label for="login">* Login: </label>
					<input type="text" name="login" id="login" size="40" maxlength="32" value="<? echo $_POST['login']; ?>"  aria-required="true"/><br/>
				
					<label for="email">* Adres e-mail: </label>
					<input type="text" name="email" id="email" size="40" value="<? echo $_POST['email']; ?>"  aria-required="true"/><br/>
					
					<div class="txt_com" id="pass_info"><? echo $TXT_passwd_strong; ?></div>	
                    
					<label for="passwd">* Hasło: </label>
					<input type="password" name="passwd" id="passwd" onkeyup="testPassword(this.value, 'divStrongPassword', 'spanStrongPassword')" size="40" maxlength="32" value=""  aria-required="true" aria-describedby="pass_info"/>
					<br/>
					
					<div class="label_txt">Siła hasła :</div>
					<div class="pass_strong_hand">
						<div id="divStrongPassword"><span id="spanStrongPassword"></span></div>
					</div>
					<br/>
					
					<label for="passwd2">* Powtórz hasło: </label>
					<input type="password" name="passwd2" id="passwd2" size="40" maxlength="32" value=""  aria-required="true"/><br/>

					<input type="checkbox" name="active" id="active" class="noformat" />
                    <label for="active" class="checkInput">Ustaw jako aktywny</label><br/>		
                    					
				
				</div>
				
				<input type="submit" value="Zapisz" class="butSave" name="save"/><input type="submit" value="Zapisz i dodaj kolejnego" class="butSaveAdd" name="saveAdd" />
			</form>	
		<?
	}
	
	if ($showEditForm)
	{
		?>
			<form method="post"  class="formEdAdd" action="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=' . $_GET['action'] . '&amp;act=editPoz&amp;id=' . $row['id_user']; ?>" name="formEd" enctype="multipart/form-data">

				<h3>Aktualizuj użytkownika</h3>
				
				<div class="formWrap">
										
					<label for="name">* Imię i nazwisko: </label>
					<input type="text" name="name" id="name" size="80" maxlength="250" value="<? echo $row['name']; ?>" aria-required="true" /><br/>
					
					<label for="login">* Login: </label>
					<input type="text" name="login" id="login" size="40" maxlength="32" value="<? echo $row['login']; ?>" aria-required="true" /><br/>

					<label for="email">* Adres e-mail: </label>
					<input type="text" name="email" id="email" size="40" value="<? echo $row['email']; ?>"  aria-required="true"/><br/>

					<div class="txt_com" id="pass_info">
						- <? echo $TXT_passwd_strong; ?><br/>
                        - Pozostaw poniższe pola puste jeśli nie zmieniasz hasła.
                    </div>	
                    				
					<label for="passwd">Hasło: </label>
					<input type="password" name="passwd" id="passwd" onkeyup="testPassword(this.value, 'divStrongPassword', 'spanStrongPassword')" size="40" maxlength="32" value="" aria-describedby="pass_info"/>
					<br/>
					
					<div class="label_txt">Siła hasła :</div>
					<div class="pass_strong_hand">
						<div id="divStrongPassword"><span id="spanStrongPassword"></span></div>
					</div>
					<br/>
					
					<label for="passwd2">Powtórz hasło: </label>
					<input type="password" name="passwd2" id="passwd2" size="40" maxlength="32" value="" />
					<span></span>
					<br/>
					
					<input type="checkbox" name="active" id="active" class="noformat"  <? if ($row['active']==1) { echo ' checked="checked" '; } ?> />
                    <label for="active" class="checkInput">Ustaw jako aktywny</label><br/>
                                        
			
				</div>
				
				<input type="submit" value="Zapisz" class="butSave" name="save"/>
			</form>	
		<?
	}	
	
	if ($showList)
	{
	?>

	<table width="100%">
		<caption>Ilość pozycji: <? echo $numRows; ?></caption>
		<tr><th width="5%">L.p</th><th width="20%">Użytkownik</th><th width="15%">Login</th><th width="15%">E-mail</th><th width="10%">Typ</th><th width="15%">Ostatnia wizyta</th><th width="7%">Aktywny</th><th width="13%">Akcja</th></tr>
		<?php
			$n = 0;
			foreach ($outRow as $row)
			{
				$n++;
				$rowColor = '';
				if ($n % 2 == 0){
					$rowColor = 'rowOdd';						
				} 
					
				$active_url = '';
				
				$noActive = '';
				$bolder = '';
				if ($row['type'] != 'admin') 
				{
					if ($row['active'] == 1) {
					    $bolder = ' class="bolder"';
						$active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=noactive&amp;id=' . $row['id_user'] . '" title="Ukryj"><img src="template/images/icoStat1.png" alt="Ukryj" class="imgAct" /></a> ';
					}
					else {
					    $noActive = 'noactive';
						$active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=active&amp;id=' . $row['id_user'] . '" title="Pokaż"><img src="template/images/icoStat0.png" alt="Pokaż" class="imgAct" /></a> ';
					}
				} else
				{
				    $bolder = ' class="bolder"';
				}
								
				echo '<tr class="'.$rowColor.' '.$noActive.'"><td>' . $n . '.</td>'
					.'<td'.$bolder.'>' . $row['name'] . '</td>'
					.'<td'.$bolder.'>' . $row['login'] . '</td>'
					.'<td>' . $row['email'] . '</td>'					
					.'<td>' . $users_type[$row['type']] . '</td>'
					.'<td>' . $row['last_visit'] . '</td>'
					.'<td class="butIcons">' . $active_url . '</td>'
					.'<td class="butIcons">';

				echo '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=edit&amp;id=' . $row['id_user'] . '" title="Edytuj pozycję"><img src="template/images/icoEdit.png" alt="Edytuj pozycję" class="imgAct" /></a> ';

				if ($row['type'] != 'admin') 
				{
				    echo '<a href="'.$PHP_SELF.'?c=priv&amp;id=' . $row['id_user'] . '" title="Uprawnienia"><img src="template/images/icoPriv.png" alt="Uprawnienia" class="imgAct" /></a> ';				    
				    echo '<a href="javascript: confirmLink(\'' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=delete&amp;id=' . $row['id_user'] . '\',\'' . $MSG_del_confirm . '\');" title="Usuń pozycję" class="delLink"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a> ';
										
				}

				echo '</td>'
					.'</tr>';
			}
		?>
	</table>
			<div class="legend">
			    <span class="bolder">Legenda:</span>
			    <?php
			    
			    include_once(CMS_TEMPL . DS . 'legend_icons.php');
			    ?>
			</div>	
    <?
    }
?>