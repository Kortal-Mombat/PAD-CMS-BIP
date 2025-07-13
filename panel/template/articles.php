<?
	echo '<h2>'. $pageTitle .'</h2>';

	if ($showList)
	{
		?>
		<ul class="navMenu">
			<li><a href="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=add&amp;idp=' . $_GET['idp']; ?>"><span class="icoAdd"></span>Dodaj artykuł</a></li>	
		</ul>
		<?
	}
	
	if ($showEditForm)
	{
		?>
		<ul class="navMenu">
			<li><a href="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp']; ?>"><span class="icoBack"></span>Powrót do listy</a></li>
            <li><a href="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=add&amp;idp=' . $_GET['idp']; ?>"><span class="icoAdd"></span>Dodaj artykuł</a></li>		
		</ul>
		<?
	}
	
	if ($showAddForm)
	{
		?>
		<ul class="navMenu">
			<li><a href="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp']; ?>"><span class="icoBack"></span>Powrót do listy</a></li>
		</ul>
		<?
	}	

?>
<div class="clear"></div>
<?
	echo $message;

	if ($showAddForm)
	{
		?>
			<form method="post"  class="formEdAdd" action="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp'] . '&amp;action=' . $_GET['action'] . '&amp;act=addPoz'; ?>" name="formAdd" enctype="multipart/form-data">
            <div id="tabs">
                <ul>
                    <li><a href="#txt">Treść</a></li>
                    <li><a href="#set">Ustawienia</a></li>
                    <li><a href="#seo">Pozycjonowanie SEO</a></li>
                    <li><a href="#photo">Zdjęcia</a></li>
                    <li><a href="#file">Pliki do pobrania</a></li>                    
                </ul>
				<div class="clear"></div>
    			<div class="formWrap">                    
                    <div id="txt">
                        <a name="txt"></a>						
                        
                        <label for="name">* Tytuł: </label><?php echo addTip('articles', 'title'); ?>
                        <input type="text" name="name" id="name" size="100" maxlength="250" value="" aria-required="true"/><br/>
                        
                        <label for="url_name">Tytuł wyświetlany w linku: </label><?php echo addTip('articles', 'url_name'); ?>
                        <input type="text" name="url_name" id="url_name" size="100" maxlength="250" value="" /><br />
			
                        <label for="lead_text">Wprowadzenie do artykułu: </label>
                        <textarea id="lead_text" name="lead_text" style="width:98%; height: 250px"></textarea><br/>	
                        
                        <label for="text">Rozwinięcie: </label>
                        <textarea id="text" name="text" style="width:98%; height: 500px"></textarea><br/>
						<?php echo addEditor('lead_text, text'); ?>
                    </div>
                    
                    <div id="set">
                        <a name="set"></a>						
    
                        <label for="pos">Kolejność: </label><?php echo addTip('articles', 'position'); ?>
                        <input type="text" name="pos" id="pos" size="4" maxlength="10" value="1" /><br/>
                        
                        <label for="ext_url">Przekierowanie:</label><?php echo addTip('articles', 'redirectInfo'); ?><br />
                        <input type="text" name="ext_url" id="ext_url" size="70" maxlength="250" value="" class="inBlock" title="Wpisz adres www na jaki ma być przekierowana strona np.:|http://www.nazwastrony.pl" /> 
                        <input type="checkbox" name="new_window" id="new_window"  <? if ($_POST['new_window']==1) { echo ' checked="checked" '; } ?> /> <label for="new_window" class="checkInput">Otwarcie w nowym oknie</label><?php echo addTip('articles', 'redirect'); ?><br/>				
                        
                        <label for="show_date">Data utworzenia dokumentu / Data wyświetlana:</label><?php echo addTip('articles', 'date_add'); ?><br />
                        <input type="text" class="datepicker inBlock" name="show_date" id="show_date" size="21" maxlength="10" value="<? echo date("Y-m-d")?>"/><br/>
                        
                        <label for="start_date">Wyświetl od:</label><?php echo addTip('articles', 'date_from'); ?><br />
                        <input type="text" class="datepicker inBlock" name="start_date" id="start_date" size="21" maxlength="10"  value="" /><br/>
                        
                        <label for="stop_date">Wyświetl do:</label><?php echo addTip('articles', 'date_to'); ?><br /> 
                        <input type="text" class="datepicker inBlock" name="stop_date" id="stop_date" size="21" maxlength="10" value="" /><br/>

                        <label for="autor">* Autor/Osoba sporządzająca:</label><?php echo addTip('articles', 'author'); ?>
                        <input type="text" name="autor" id="autor" size="70" maxlength="250" value=""  aria-required="true"/><br/>				

                        <label for="wprowadzil">Osoba wprowadzająca:</label>
                        <input type="text" name="wprowadzil" id="wprowadzil" size="70" maxlength="250" value="<? echo $_SESSION['userData']['name']; ?>" /><br/>	

                        <label for="podmiot">* Podmiot udostępniający informację:</label>
                        <input type="text" name="podmiot" id="podmiot" size="70" maxlength="250" value=""  aria-required="true"/><br/>	
            
                    </div>
                     
                       
                    <div id="seo">
                        <a name="seo"></a>						
                
                        <label for="meta_title">Meta title (tytuł):</label><?php echo addTip('articles', 'metaTitle'); ?>
                        <input type="text" name="attrib[meta_title]" id="meta_title" size="100" value=""  /><br/>

                        <label for="meta_keywords">Meta keywords (słowa kluczowe):</label><?php echo addTip('articles', 'metaKey'); ?>
                        <input type="text" name="attrib[meta_keywords]" id="meta_keywords" size="100" value=""  /><br/>			
			
                        <label for="meta_desciption">Meta desciption (opis):</label><?php echo addTip('articles', 'metaDesc'); ?>
                        <input type="text" name="attrib[meta_desciption]" id="meta_desciption" size="100" value=""  /><br/>
                    </div>    
                    
                    <div id="photo">
                        <a name="photo"></a>						
    					<div class="txt_com">Zdjęcia można dodać po utworzeniu strony. Należy kliknąć ikonę edycji i przejść do zakładki "Zdjęcia"</div>
                    </div>     
                    
                    <div id="file">
                        <a name="file"></a>	
                        <div class="txt_com">Pliki można dodać po utworzeniu strony. Należy kliknąć ikonę edycji i przejść do zakładki "Pliki do pobrania"</div>					
                    </div>  
                    
                </div>                                                                       
			</div>
				
            <div id="add_bip_fileds">
                <input type="checkbox" name="save_opis_zm" id="save_opis_zm" class="inBlock" checked="checked" /> <label for="save_opis_zm" class="checkInput">Zapisz zmiany w rejestrze</label> <br/><br/>					
                
                <label for="data_publ">Data zmiany dokumentu:</label><br />
                <input type="text" class="datepicker inBlock" name="data_publ" id="data_publ" size="23" maxlength="30"  value="<?php echo date("Y-m-d H:i:s")?>" /><br/><br/>
                                        
                <label for="opis_zm">Opis zmiany zapisanej w rejestrze:</label>
                <input type="text" name="opis_zm" id="opis_zm" size="70" maxlength="250" value="" /><br/><br/>
                
                <div class="txt_com"> * Informacje o zmianach będą widoczne w stopce artykułu</div>
            </div>	
                            
            <input type="submit" value="Zapisz" class="butSave" name="save"/><input type="submit" value="Zapisz i dodaj kolejną" class="butSaveAdd" name="saveAdd"/>
			</form>	     
     
        <?
	}
        
	if ($showEditForm)
	{
		if ($showView)
		{
			echo '<script type="text/javascript">'
					.'window.open("../index.php?c=view&id_viewer='.$idViewer.'&type='.$_SESSION['type_to_files'].'&idp=' . $_GET['idp'] . '", "view");'
				.'</script>';
		}		
		?>
			<form method="post"  class="formEdAdd" action="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp'] . '&amp;action=' . $_GET['action'] . '&amp;act=editPoz&amp;id=' . $row['id_art']; ?>" name="formEd" enctype="multipart/form-data">
            <div id="tabs">
                <ul>
                    <li><a href="#txt">Treść</a></li>
                    <li><a href="#set">Ustawienia</a></li>
                    <li><a href="#stat">Statystyki</a></li>
                    <li><a href="#seo">Pozycjonowanie SEO</a></li>
                    <li><a href="#photo" id="tabPhoto">Zdjęcia</a></li>
                    <li><a href="#file" id="tabFiles">Pliki do pobrania</a></li>                    
                </ul>
				<div class="clear"></div>
    			<div class="formWrap">                    
                    <div id="txt">
                        <a name="txt"></a>						
                        
                        <label for="name">* Tytuł: </label><?php echo addTip('articles', 'title'); ?>
                        <input type="text" name="name" id="name" size="100" maxlength="250" value="<? echo $row['name']; ?>"  aria-required="true"/><br/>
			
                        <label for="url_name">Tytuł wyświetlany w linku: </label><?php echo addTip('articles', 'url_name'); ?>
                        <input type="text" name="url_name" id="url_name" size="100" maxlength="250" value="<? echo $row['url_name']; ?>" /><br />
                        
                        <label for="lead_text">Wprowadzenie do artykułu: </label>
                        <textarea id="lead_text" name="lead_text" style="width:98%; height: 250px"><? echo $row['lead_text']; ?></textarea><br/>	
                                                
                        <label for="text">Rozwinięcie: </label>
                        <textarea id="text" name="text" style="width:98%; height: 500px"><? echo $row['text']; ?></textarea><br/>
						<?php echo addEditor('lead_text, text'); ?>						
                        
                        <textarea id="old_text" name="old_text" class="hide_all"><? echo $row['text']; ?></textarea>
                    </div>
                    
                    <div id="set">
                        <a name="set"></a>						
                        <label for="pos">Kolejność: </label><?php echo addTip('articles', 'position'); ?>
                        <input type="hidden" name="old_pos" value="<? echo $row['pos']; ?>" />
                        <input type="text" name="pos" id="pos" size="4" maxlength="10" value="<? echo $row['pos']; ?>" /><br/>
                            
                        <label for="ext_url">Przekierowanie:</label><?php echo addTip('articles', 'redirectInfo'); ?><br/>
                        <input type="text" name="ext_url" id="ext_url" size="70" maxlength="250" value="<? echo $row['ext_url']; ?>" class="inBlock" title="Wpisz adres www na jaki ma być przekierowana strona np.:|http://www.nazwastrony.pl" /> 
                        <input type="checkbox" name="new_window" id="new_window"  <? if ($row['new_window']==1) { echo ' checked="checked" '; } ?> /> <label for="new_window" class="checkInput">Otwarcie w nowym oknie</label><?php echo addTip('articles', 'redirect'); ?><br/>				
                        
                        <label for="show_date">Data utworzenia dokumentu / Data wyświetlana:</label><?php echo addTip('articles', 'date_add'); ?><br /> 
                        <input type="text" class="datepicker inBlock" name="show_date" id="show_date" size="21" maxlength="10" value="<? echo $row['show_date']; ?>"/><br/>
                        
                        <label for="start_date">Wyświetl od:</label><?php echo addTip('articles', 'date_from'); ?><br />
                        <input type="text" class="datepicker inBlock" name="start_date" id="start_date" size="21" maxlength="10"  value="<? echo $row['start_date']; ?>" /><br/>
                        
                        <label for="stop_date">Wyświetl do:</label><?php echo addTip('articles', 'date_to'); ?><br />
                        <input type="text" class="datepicker inBlock" name="stop_date" id="stop_date" size="21" maxlength="10" value="<? echo $row['stop_date']; ?>" /><br/>

                        <label for="autor">* Autor/Osoba sporządzająca:</label><?php echo addTip('articles', 'author'); ?>
                        <input type="text" name="autor" id="autor" size="70" maxlength="250" value="<? echo $row['author']; ?>"  aria-required="true"/><br/>	

                        <label for="wprowadzil">Osoba wprowadzająca:</label>
                        <input type="text" name="wprowadzil" id="wprowadzil" size="70" maxlength="250" value="<? echo $row['wprowadzil']; ?>" /><br/>	
                        
                        <label for="podmiot">* Podmiot udostępniający informację:</label>
                        <input type="text" name="podmiot" id="podmiot" size="70" maxlength="250" value="<? echo $row['podmiot']; ?>"  aria-required="true"/><br/>	                                                			
                    </div>
                     
                    <div id="stat">
                        <a name="stat"></a>						
                        
                        <div class="label_txt inline">Ilość wyświetleń:</div> <span><? echo $row['counter']; ?></span><br/>
                        
                        <div class="label_txt inline">Data utworzenia:</div> <span><? echo $row['create_date']; ?></span><br/>
                        
                        <div class="label_txt inline">Data ostatniej aktualizacji:</div> <span><? echo $row['modified_date']; ?></span><br/>
    
                    </div>
                        
                    <div id="seo">
                        <a name="seo"></a>						
    
                        <label for="meta_title">Meta title (tytuł):</label><?php echo addTip('articles', 'metaTitle'); ?>
                        <input type="text" name="attrib[meta_title]" id="meta_title" size="100" value="<? echo $attrib['meta_title']; ?>"  /><br/>
                
                        <label for="meta_keywords">Meta keywords (słowa kluczowe):</label><?php echo addTip('articles', 'metaKey'); ?>
                        <input type="text" name="attrib[meta_keywords]" id="meta_keywords" size="100" value="<? echo $attrib['meta_keywords']; ?>"  /><br/>			
			
                        <label for="meta_desciption">Meta desciption (opis):</label><?php echo addTip('articles', 'metaDesc'); ?>
                        <input type="text" name="attrib[meta_desciption]" id="meta_desciption" size="100" value="<? echo $attrib['meta_desciption']; ?>"  /><br/>
    
                    </div>    
                    
                    <div id="photo">
                        <a name="photo"></a>						
    
                        <div class="photoList"></div>
                        <script type="text/javascript">
						$(document).ready(function(){  
							$('#tabPhoto').click(function() { 
								$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>');
							});
							<?php
							if ($_GET['autoload'] == 1)
							{
							?>
								$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>');
							<?php
							}
							?>			    
						}); 
                        </script>    
                    </div>     
                    
                    <div id="file">
                        <a name="file"></a>						
    					
                        <div class="fileList"></div>
                        <script type="text/javascript">
							$(document).ready(function(){  
								$('#tabFiles').click(function() { 
									$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>');
								});
								<?php
								if ($_GET['autoload'] == 1)
								{
								?>
									$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>');  
								<?php
								}
								?>
							}); 
                        </script>
                    </div>  
                </div>                                                                       
			</div>
				
            <div id="add_bip_fileds">
                <input type="checkbox" name="save_opis_zm" id="save_opis_zm" class="inBlock" checked="checked" /> <label for="save_opis_zm" class="checkInput">Zapisz zmiany w rejestrze</label> <br/><br/>					
                
                <label for="data_publ">Data zmiany dokumentu:</label><br />
                <input type="text" class="datepicker inBlock" name="data_publ" id="data_publ" size="23" maxlength="30"  value="<?php echo date("Y-m-d H:i:s")?>" /><br/><br/>

                <label for="opis_zm">Opis zmiany zapisanej w rejestrze:</label>
                <input type="text" name="opis_zm" id="opis_zm" size="70" maxlength="250" value="" /><br/><br/>
                
               <div class="txt_com"> * Informacje o zmianach będą widoczne w stopce artykułu</div>
            </div>	
                            
			<input type="submit" value="Podgląd" class="butView tip" name="view" title="Podgląd będzie otwarty w nowym oknie" />
			<input type="submit" value="Zapisz" class="butSave" name="save"/>
			<input type="submit" value="Zapisz i powróć do listy" class="butSaveAdd" name="saveEdit"/>
			</form>	
		<?
	}
		
	if ($showList)
	{
		if ($numRows > 0)
		{
		?>
		<div id="articleList">
		    
		    <form action="<?php echo $PHP_SELF.'?c='.$_GET['c'].'&amp;idp='.$_GET['idp'].'&amp;action=number' ?>" method="post" id="articlesNumber">
			<label for="number">Wyświetlanych artykułów: <span class="hide">Nastąpi automatyczne przeładowanie strony</span></label>
			<select id="number" name="number">
			    <?php foreach ($arrArticlesNumber as $value): ?>
			    <option value="<?php echo $value?>"<?php if ($value == $sql_limit){echo ' selected="selected"';}?>><?php echo $value?></option>
			    <?php endforeach;?>
			</select>
		    </form>
		
        <form action="<? echo $PHP_SELF.'?c='.$_GET['c'].'&amp;idp='.$_GET['idp'].'&amp;action=change' ?>" method="post" name="f_sel">
        <table width="100%" id="rowList">
			<caption>Ilość pozycji: <? echo $numRows; ?></caption>
			<thead>
				<tr><th width="5%"></th>
                <th width="4%">L.p</th>
                <th width="40%">Tytuł</th>
                <th width="12%">Data wyświetlana</th>
                <th width="15%">Wyświetl od-do</th>
                <th width="6%">Pokazać</th>
                <th width="18%">Akcja</th></tr>
			</thead>
			<tbody>
			<?
				$n = 1;
				$i = 0;
				foreach ($outRow as $row)
				{
					$i++;

					$rowColor = '';
					if ($n % 2 == 0){
						$rowColor = 'rowOdd';						
					}					

					$active_url = '';
					
					$noActive = '';
					$bolder = '';
					if ($row['active'] == 1) {
					    $bolder = ' bolder';
						$active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=noactive&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '" title="Zmień na ukryj"><img src="template/images/icoStat1.png" alt="Ukryj" class="imgAct" /></a> ';
					}
					else {
					    $noActive = 'noactive';
						$active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=active&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '" title="Zmień na pokaż"><img src="template/images/icoStat0.png" alt="Pokaż" class="imgAct" /></a> ';
					}
					
					$row['start_date'] = substr($row['start_date'], 0, 10);
					$row['stop_date'] = substr($row['stop_date'], 0, 10);
					
					if ($row['start_date']!='0000-00-00' && $row['stopt_date']!='0000-00-00')
						$odDo = $row['start_date'].'<br/>'.$row['stop_date'];
					else
						$odDo = 'bez przerwy';
						
					if (!($row['start_date']<=$shortDate && $row['stop_date']>=$shortDate) && ($row['start_date']!='0000-00-00' && $row['stop_date']!='0000-00-00') || $row['active']==0) 
					{
						$noActive = 'noactive';
					}
														
					echo '<tr class="'.$rowColor.' '.$noActive.'" id="artId_' . $row['id_art'] . '">'
						.'<td><input type="checkbox" name="m_'.$i.'" id="m_'.$i.'" value="'.$row['id_art'].'" /></td>'
						.'<td class="artPos">' . ($i+$sql_start) . '.</td>'
						.'<td><label for="m_'.$i.'" class="">' . $row['name'] . '</label></td>'
						.'<td>' . substr($row['show_date'], 0, 10) . '</td>'
						.'<td>' . $odDo . '</td>'
						.'<td class="butIcons">' . $active_url . '</td>'
						.'<td class="butIcons">';
	
					if ($row['pos'] > 1)
						echo '<a href="' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=posTop&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '&amp;s=' . $_GET['s'] . '" title="Przesuń do góry"><img src="template/images/icoSortTop.png" alt="Przesuń do góry"></a> ';
					else
						echo '<img src="template/images/icoSortTop_.png" alt="" /> ';
					
					if ($row['pos'] < $numRows)
						echo '<a href="' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=posBot&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '&amp;s=' . $_GET['s'] . '" title="Przesuń na dół"><img src="template/images/icoSortDown.png" alt="Przesuń na dół"></a> ';
					else
						echo '<img src="template/images/icoSortDown_.png" alt="" /> ';
						
					echo '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=edit&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '" title="Edytuj pozycję"><img src="template/images/icoEdit.png" alt="Edytuj pozycję" class="imgAct" /></a> ';
					echo '<a href="'.$PHP_SELF.'?c=register&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '" title="Zobacz rejestr zmian"><img src="template/images/icoRegister.png" alt="Zobacz rejestr zmian" class="imgAct" /></a> ';
					echo '<a href="javascript: confirmLink(\'' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=delete&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '&amp;s=' . $_GET['s'] . '\',\'' . $MSG_del_confirm . '\');" title="Usuń pozycję" class="delLink"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a> ';
					
					echo '</td>'
					    .'</tr>';
						
					$n++;
				}
				
			?>
			</tbody>
			<tfoot>
                <tr>
                	<td colspan="7" class="changeAction">
			    
                <div class="legend">
                    <span class="bolder">Legenda:</span>
                    <?php
                   // include_once(CMS_TEMPL . DS . 'legend_position.php');
                    include_once(CMS_TEMPL . DS . 'legend_icons.php');
                    ?>
                </div>			    
			    
			    <div class="changeActionWrapper">
			    <img src="template/images/icoArrowSel.png" alt="" /> <a href="#" onclick="on_box(); return false;">Zaznacz wszystkie</a> / <a href="#" onclick="off_box(); return false;">Odznacz wszystkie</a>
			    </div>
			    <div class="changeActionSelect">
			    
				<fieldset class="changeTitle">
                    <legend>Zaznaczone:</legend>
                    <div class="changeDiv"><input name="ch_type" id="act1" type="radio" value="show_sel" /> <label for="act1">Pokaż</label></div>
                    <div class="changeDiv"><input name="ch_type" id="act2" type="radio" value="hide_sel" /> <label for="act2">Ukryj</label></div>
                    <div class="changeDiv"><input name="ch_type" id="act3" type="radio" value="del_sel" /> <label for="act3">Usuń</label></div>
                    <div class="changeDiv margTop"><input name="ch_type" id="act4" type="radio" value="move_sel" /> <label for="act4">Przenieś do:</label> 
                    <label for="sel_gr" class="hide">Wybierz kategorię:</label>
                    <select name="gr" id="sel_gr">
                        <?php
                        $n = 0;
                        foreach ($outMenu as $value)
                        {
							echo '<optgroup label="' . $value['name'] . '">';
							get_menu($menuTree[$n], 0, 0);
							echo '</optgroup>';
							$n++;
                        }
                        ?>
                    </select></div>
				</fieldset>
                
				<br class="clear" />
			    </div>
                 <div>
			    <input type="submit" value=" Wykonaj " class="button" />
				</div>
                	</td>
                </tr>
			</tfoot>
		</table>
        </form>
        
        </div>
		<?
			$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;idp=' . $_GET['idp'] . '&amp;s=';
			include (CMS_TEMPL . DS . 'pagination.php');	
		}		
	}

?>


<script type="text/javascript">
// <![CDATA[
	$(document).ready(function() {
	    $(function() {
			$('#name').friendurl({id : 'url_name', divider: '-', transliterate: true});
	    });	    
	    
		$(function() {
			var fixHelper = function(e, ui) {
				ui.children().each(function() {
					$(this).width($(this).width());
				});
				return ui;
			};		

		
		$('.formEdAdd').bind('submit', function(){
			var valid = true;
		
			if($('#name').val()==''){
				$('#name').addClass('invalid');
				valid = false;
			}else{
				$('#name').removeClass('invalid');
			}
				
			if($('#autor').val()==''){
				$('#autor').addClass('invalid');
				valid = false;
			}else{
				$('#autor').removeClass('invalid');
			}
			
			if($('#podmiot').val()==''){
				$('#podmiot').addClass('invalid');
				valid = false;
			}else{
				$('#podmiot').removeClass('invalid');
			}
										
			if(!valid){
				alert('Wypełnij wszystkie oznaczone gwiazdką pola i spróbuj ponownie.');
				return false;
			}
		});
		
		$('#number').on('change', function(){
		    $('#articlesNumber').submit();
		});
				
	});
	
 });	  	
// ]]>	
</script>



