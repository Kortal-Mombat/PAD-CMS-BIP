<?php
	echo '<h2>'. $pageTitle .'</h2>';

	if ($showList)
	{
		?>
		<ul class="navMenu">
			<li><a href="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=add'; ?>"><span class="icoAdd"></span>Dodaj zakładkę</a></li>	
		</ul>
		<?
	}
	
	if ($showEditForm || $showAddForm)
	{
		?>
		<ul class="navMenu">
			<li><a href="<?php echo $PHP_SELF.'?c=' . $_GET['c']; ?>"><span class="icoBack"></span>Powrót do listy</a></li>
		</ul>
		<?php
	}
	
	// komuniakt dla menu stopki
	if ($_SESSION['mt'] == 'ft')
	{
		echo '<div class="txt_com">W menu stopki nie zagnieżdżaj zakładek. Zakładki zagnieżdżone nie będą widoczne.</div>';
	}	

?>
<div class="clear"></div>
<?php
	echo $message;

	if ($showAddForm)
	{
		?>
            <form method="post"  class="formEdAdd" action="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=' . $_GET['action'] . '&amp;act=addPoz'; ?>" name="formAdd" enctype="multipart/form-data">
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
			
                        <label for="page">Dodaj w: </label><?php echo addTip('menu', 'group'); ?>
                        <select name="gr" id="page">
                            
                            <option value="0">Zakładka główna</option>
                            
                            <optgroup label="Istniejące zakładki">
                            <?php
                            get_menu($menuTree, 0, 0, 0, $depthTree);
                            ?>
                            </optgroup>
                        </select>
                        
                        <label for="name">* Tytuł: </label><?php echo addTip('menu', 'title'); ?>
                        <input type="text" name="name" id="name" size="100" maxlength="250" value="" aria-required="true" /><br/>
			
                        <label for="url_name">Tytuł wyświetlany w linku: </label><?php echo addTip('menu', 'url_name'); ?>
                        <input type="text" name="url_name" id="url_name" size="100" maxlength="250" value="" /><br />
                                    
                        <label for="text">Treść: </label>
                        <textarea id="text" name="text" style="width:98%; height: 500px"></textarea><br/>
						<?php echo addEditor('text'); ?>
                    </div>
			    
		    
                    
                    <div id="set">
                        <a name="set"></a>						
    
                        <label for="ext_url">Przekierowanie:</label><?php echo addTip('menu', 'redirectInfo'); ?> <br />
                        <input type="text" name="ext_url" id="ext_url" size="70" maxlength="250" value="" class="inBlock" title="Wpisz adres www na jaki ma być przekierowana zakładka, np.: http://www.nazwastrony.pl" /> 
                        <input type="checkbox" name="new_window" id="new_window"  <? if ($_POST['new_window']==1) { echo ' checked="checked" '; } ?> /> <label for="new_window" >Otwarcie w nowym oknie</label><?php echo addTip('menu', 'redirect'); ?> <br/>				
                        
                        <label for="show_date">Data utworzenia dokumentu/Data wyświetlana:</label><?php echo addTip('menu', 'date_add'); ?><br />
                        <input type="text" class="datepicker inBlock" name="show_date" id="show_date" size="21" maxlength="10" value="<? echo date("Y-m-d")?>"/><br/>
                        
                        <label for="art_num">Ilość artykułów w zakładce:</label><?php echo addTip('menu', 'articles'); ?> 
                        <input type="text" name="attrib[art_num]" id="art_num" size="21" maxlength="3"  value=""/><br/>
                        
                        <label for="autor">* Autor/Osoba sporządzająca:</label><?php echo addTip('menu', 'author'); ?>
                        <input type="text" name="autor" id="autor" size="70" maxlength="250" value="" aria-required="true" /><br/>				

                        <label for="wprowadzil">Osoba wprowadzająca:</label>
                        <input type="text" name="wprowadzil" id="wprowadzil" size="70" maxlength="250" value="<? echo $_SESSION['userData']['name']; ?>" /><br/>	                        			
    
                        <label for="podmiot">* Podmiot udostępniający informację:</label>
                        <input type="text" name="podmiot" id="podmiot" size="70" maxlength="250" value=""  aria-required="true" /><br/>	
    
                    </div>
                     
                       
                    <div id="seo">
                        <a name="seo"></a>						
					
                    	<p>Elementy ułatwiające pozycjonowanie strony w wyszukiwarkach, np. Google.</p>
		    		
                    	<label for="meta_title">Meta title (tytuł):</label><?php echo addTip('menu', 'metaTitle'); ?>
						<input type="text" name="attrib[meta_title]" id="meta_title" size="100" value=""  /><br/>
    
                        <label for="meta_keywords">Meta keywords (słowa kluczowe):</label><?php echo addTip('menu', 'metaKey'); ?>
                        <input type="text" name="attrib[meta_keywords]" id="meta_keywords" size="100" value=""  /><br/>
			
                        <label for="meta_desciption">Meta desciption (opis):</label><?php echo addTip('menu', 'metaDesc'); ?>
                        <input type="text" name="attrib[meta_desciption]" id="meta_desciption" size="100" value=""  /><br/>			
			
                    </div>    
                    
                    <div id="photo">
                        <a name="photo"></a>						
    					<div class="txt_com">Zdjęcia można dodać po utworzeniu zakładki. Należy kliknąć ikonę edycji i przejść do zakładki "Zdjęcia"</div>
                    </div>     
                    
                    <div id="file">
                        <a name="file"></a>	
                        <div class="txt_com">Pliki można dodać po utworzeniu zakładki. Należy kliknąć ikonę edycji i przejść do zakładki "Pliki do pobrania"</div>					
                    </div>  
                    
                </div>                                                                       
			</div>
			
            <div id="add_bip_fileds">
                <input type="checkbox" name="save_opis_zm" id="save_opis_zm" class="inBlock" checked="checked" /> <label for="save_opis_zm" class="checkInput">Zapisz zmiany w rejestrze</label> <br/> <br/>					
                
                <label for="data_publ">Data zmiany dokumentu:</label><br /> 
                <input type="text" class="datepicker inBlock" name="data_publ" id="data_publ" size="23" maxlength="30"  value="<?php echo date("Y-m-d H:i:s")?>" /><br/><br/>
                
                <label for="opis_zm">Opis zmiany zapisanej w rejestrze:</label>
                <input type="text" name="opis_zm" id="opis_zm" size="70" maxlength="250" value="" /><br/><br/>
                
                <div class="txt_com"> * Informacje o zmianach będą widoczne w stopce artykułu</div>
            </div>	
                        	
            <input type="submit" value="Zapisz" class="butSave" name="save"/>
            <input type="submit" value="Zapisz i dodaj kolejną" class="butSaveAdd" name="saveAdd"/>
			</form>	     
     
        <?php
	}
        
	if ($showEditForm)
	{
		if ($showView)
		{
			echo '<script type="text/javascript">'
					.'window.open("../index.php?c=view&id_viewer='.$idViewer.'&type='.$_SESSION['type_to_files'].'", "view");'
				.'</script>';
		}
		?>
			<form method="post"  class="formEdAdd" action="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=' . $_GET['action'] . '&amp;act=editPoz&amp;id=' . $row['id']; ?>" name="formEd" enctype="multipart/form-data">
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
			
                        <label for="page">Przenieś do: </label><?php echo addTip('menu', 'group'); ?>
                        <select name="gr" id="page">
                            
                            <option value="-1">Nie przenoś</option>
                            <option value="0">Zakładka główna</option>
                            <optgroup label="Istniejące zakładki">
                            <?php
                            get_menu($menuTree, 0, 0, 0, $depthTree);
                            ?>
                            </optgroup>
                        </select>
                                                
                        <label for="name">* Tytuł: </label><?php echo addTip('menu', 'title'); ?>
                        <input type="text" name="name" id="name" size="100" maxlength="250" value="<? echo $row['name']; ?>"  aria-required="true"/><br/>

                        <label for="url_name">Tytuł wyświetlany w linku: </label><?php echo addTip('menu', 'url_name'); ?>
                        <input type="text" name="url_name" id="url_name" size="100" maxlength="250" value="<? echo $row['url_name']; ?>" /><br />
                        
                        <label for="text">Treść: </label>
                        <textarea id="text" name="text" style="width:98%; height: 500px"><? echo $row['text']; ?></textarea><br/>
						<?php echo addEditor('text'); ?>
                        
                        <textarea id="old_text" name="old_text" class="hide_all"><? echo $row['text']; ?></textarea>
                    </div>
                    
                    <div id="set">
                        <a name="set"></a>						
    
                        <label for="ext_url">Przekierowanie:</label><?php echo addTip('menu', 'redirectInfo'); ?><br />
                        <input type="text" name="ext_url" id="ext_url" size="70" maxlength="250" value="<? echo $row['ext_url']; ?>" class="inBlock" title="Wpisz adres www na jaki ma być przekierowana zakładka, np.:|http://www.nazwastrony.pl" />
                        <input type="checkbox" name="new_window" id="new_window"  <? if ($row['new_window']==1) { echo ' checked="checked" '; } ?> /> <label for="new_window" class="checkInput">Otwarcie w nowym oknie</label><?php echo addTip('menu', 'redirect'); ?><br/>				
                        
                        <label for="show_date">Data utworzenia dokumentu / Data wyświetlana:</label><?php echo addTip('menu', 'date_add'); ?><br />
                        <input type="text" class="datepicker inBlock" name="show_date" id="show_date" size="21" maxlength="10" value="<? echo $row['show_date']; ?>"/><br/>
                        
                        <label for="art_num">Ilość artykułów w zakładce:</label><?php echo addTip('menu', 'articles'); ?>
                        <input type="text" name="attrib[art_num]" id="art_num" size="21" maxlength="3"  value="<? echo $attrib['art_num']; ?>"/><br/>
                        
                        <label for="autor">* Autor/Osoba sporządzająca:</label><?php echo addTip('menu', 'author'); ?>
                        <input type="text" name="autor" id="autor" size="70" maxlength="250" value="<? echo $row['author']; ?>" aria-required="true"/><br/>		

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
    
                        <label for="meta_title">Meta title (tytuł):</label><?php echo addTip('menu', 'metaTitle'); ?>
                        <input type="text" name="attrib[meta_title]" id="meta_title" size="100" value="<? echo $attrib['meta_title']; ?>"  /><br/>
                
                        <label for="meta_keywords">Meta keywords (słowa kluczowe):</label><?php echo addTip('menu', 'metaKey'); ?>
                        <input type="text" name="attrib[meta_keywords]" id="meta_keywords" size="100" value="<? echo $attrib['meta_keywords']; ?>"  /><br/>
			
                        <label for="meta_desciption">Meta desciption (opis):</label><?php echo addTip('menu', 'metaDesc'); ?>
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
                            
			<input type="submit" value="Podgląd" title="Podgląd będzie otwarty w nowym oknie" class="butView tip" name="view" />
            <input type="submit" value="Zapisz" class="butSave" name="save"/>
            <input type="submit" value="Zapisz i powróć do listy" class="butSaveAdd" name="saveEdit"/>
			</form>	
		<?php
	}
		
	if ($showList)
	{
		echo '<form action="'.$PHP_SELF.'?c=page&amp;mp='.$_GET['mp'].'&amp;mt='.$_GET['mt'].'&amp;action=setPos" method=post name="f_sel">';
		echo '<div id="menuTreeHead" aria-hidden="true">'
				.'Nazwa'
				.'<div class="menuTreeCells">'
					.'<div class="menuTreeShow">Pokazać</div>'
					.'<div class="menuTreePos">Pozycja</div>'
					.'<div class="menuTreeAction">Akcje</div>'
				.'</div>'
			.'</div>';
				
		echo '<div id="menuTree">';			
		get_panel_menu_tree();
		echo '</div>';
		
		echo '<div id="menuTreeButton">'
			.'<input name="change" type="submit" value="Zmień" class="tip" title="Potwierdź zmianę ustalonych pozycji" />'
			.'</div>';
		echo '</form>';
		?>
			<div class="legend">
			    <span class="bolder">Legenda:</span>
			    <?php
			    include_once(CMS_TEMPL . DS . 'legend_position.php');
			    include_once(CMS_TEMPL . DS . 'legend_icons.php');
			    ?>
			</div>					
			
		<?php
	}
	echo '<div id="result"></div>';
?>
			

<script type="text/javascript">
// <![CDATA[
	$(document).ready(function() {
	    $(function() {
			$('#name').friendurl({id : 'url_name', divider: '-', transliterate: true});
	    });
		
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
		
	});
// ]]>	
</script>		
