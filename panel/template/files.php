<div id="filesWrapper">

<script language="javascript" type="text/javascript">
    
	setTimeLimit();
	
    function setUploaderHeight(uploaderHeight)
    {
		$('#upload').height(uploaderHeight);
    }
    
    function reloadPage(filesCompleted)
    {
		window.onbeforeunload = null;
		$.post('ssv.php', {files: filesCompleted}, function(ret){
			$('#file .fileList').load('index.php?c=files&idp=<?php echo $_GET['idp']; ?>&id=<?php echo $_GET['id']; ?>&action=addFiles');
		});
    }
    
    function exit(evt) {
		var message = "Trwa przesyłanie plików. Jeśli opuścisz stronę nie wszystkie pliki zostaną przesłane.\r\nOpuścić stronę?";
		
		if (typeof evt == 'undefined') {
			evt = window.event;
		}
		if (evt) {
			evt.returnValue = message;
		}
		return message;
    }

    function uploadStarted(uploadedFile)
    {
		window.onbeforeunload = exit;
    }
    
</script>

<script type="text/javascript">
	
	/* kontrolery akcji */
	
	$("#file .butActive").click(function() { 
		var file_id = $(this).attr('id').substr(2);
		$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+file_id+'&action=noactive');
		return false;
	}); 
	
	$("#file .butNoActive").click(function() { 
		var file_id = $(this).attr('id').substr(2);
		$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+file_id+'&action=active');
		return false;
	}); 	
	
	$("#file .butEdit").click(function() { 
		var file_id = $(this).attr('id').substr(4);
		$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+file_id+'&action=edit');
                $(window).scrollTop(220);
		return false;
	}); 	

	$("#file .butDel").click(function() { 
		var file_id = $(this).attr('id').substr(5);
		
		qs = confirm('<? echo $MSG_del_confirm; ?>');
		if(qs)
		{
			$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+file_id+'&action=delete');
                        $(window).scrollTop(220);
			return false;
		}		
	}); 	

	$("#file .butPosTop").click(function() { 
		var file_id = $(this).attr('id').substr(4);
		$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+file_id+'&action=posTop');
		return false;
	}); 

	$("#file .butPosBot").click(function() { 
		var file_id = $(this).attr('id').substr(4);
		$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+file_id+'&action=posBot');
		return false;
	}); 
	

	
	$("#file .butBack").click(function() { 
		$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>');
		return false;
	}); 
	
	$("#file .butSaveAdd").click(function() { 
		var data = {
			file_old_pos : $('#file_old_pos').val(),
			file_pos : $('#file_pos').val(),
			file_name : $('#file_name').val(),
			file_keywords : $('#file_keywords').val()
		};
		$.post('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf=<? echo $_GET['idf']; ?>&action=edit&act=editPoz', data, function(theResponse){
				$("#file .fileList").html(theResponse);
		});
				
		return false;
	}); 
	
	$("#file .butAddFile").click(function() { 
		$('#file .fileList').load('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&action=copy');
         $(window).scrollTop(220);
		return false;
	}); 
	
	$("#file .butSelUploadFiles").click(function() { 
		var data = {
			filesNum : $('#filesNum').val(),
		};

		$.post('index.php?c=files&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&action=copy&act=selUploadFiles', data, function(theResponse){
			$("#file .fileList").html(theResponse);
		});
				
		return false;
	});	
</script>

<?php
	if ($showEditForm || $copyFile)
	{
		?>
		<ul class="navMenu">
			<li><a href="#" class="butBack"><span class="icoBack"></span>Powrót do listy</a></li>
		</ul>
		<?
	}

?>
<div class="clear"></div>
<?
	echo $message;
	
	switch ($_SESSION['type_to_files'])
	{
		case 'article'	: $formURL = $PHP_SELF.'?c=articles&amp;idp='.$_GET['idp'].'&amp;id='.$_GET['id'].'&amp;action=edit&amp;filesNum=' . $_POST['filesNum'] . '&amp;act=uploadFiles&amp;autoload=1#file'; break;
		case 'page'		: $formURL = $PHP_SELF.'?c=page&amp;action=edit&amp;id='.$_GET['id'].'&amp;filesNum=' . $_POST['filesNum'] . '&amp;act=uploadFiles&amp;autoload=1#file'; break;
	}
	
	if ($copyFile) 
	{
	
		echo '<form action="" method="post">'
			.'<h3>Prześlij pliki</h3>'
			.'<div class="formWrap">'
			.'<label for="filesNum">Wybierz ilość plików do przesłania:</label> '
			.'<select name="filesNum" id="filesNum" size="1" style="display: inline;">';
		for ($k=1; $k<=10; $k++)
			echo '<option>'.$k.'</option>';
		echo '</select> <input type="submit" value=" Dalej " class="butSelUploadFiles"  style="display: inline;" /> </form>';
		
			if ($copyFileSel) {
				echo '<form action="'.$formURL.'" method="post" enctype="multipart/form-data">';
				for ($i=1; $i<=$_POST['filesNum']; $i++)
				{
					echo '<label for="file'.$i.'">Wybierz plik nr '.$i.' :</label> <input name="file'.$i.'" id="file'.$i.'" type="file" size="30"><br/>';
					echo '<label for="opis'.$i.'">Opis pliku nr '.$i.' :</label> <input name="opis'.$i.'" id="opis'.$i.'" type="text" size="70"><br/>';
				}
				echo '<input type="submit" value=" Prześlij na serwer " class="butUploadFiles" /> </form>';
			}	
	
		
		echo '</div>';
	}
	
	if ($showEditForm)
	{
		echo '<h2>'. $pageTitle .'</h2>';
		?>
			<form method="post" id="fileForm"  class="formEdAdd" action="" name="formEd" enctype="multipart/form-data">

                <label for="file_pos">Kolejność: </label>
                <input type="hidden" name="file_old_pos" id="file_old_pos" value="<? echo $row['pos']; ?>" />
                <input type="text" name="file_pos" id="file_pos" size="4" maxlength="10" value="<? echo $row['pos']; ?>" /><br/>

                <label for="file_name">Nazwa: </label>
                <input type="text" name="file_name" id="file_name" size="100" maxlength="250" value="<? echo $row['name']; ?>" /><br/>
                
                <label for="file_keywords">Słowa kluczowe: </label>
                <input type="text" name="file_keywords" id="file_keywords" size="100" maxlength="250" value="<? echo $row['keywords']; ?>" class="tip" title="Słowa kluczowe służą łatwiejszemu wyszukiwaniu plików na Twojej stronie."/><br/>
				
			<input type="submit" value="Zapisz i powróć do listy" class="butSaveAdd" name="saveEdit"/>
			</form>	
		<?
	}
		
	if ($showList)
	{
		?>
		<div id="articleList">
			<div id="filesMain">
			<?php
			if ($numRows > 0)
			{
			?>    
      		
                <table width="100%">
                <caption>Ilość pozycji: <? echo $numRows; ?></caption>
                            
                <tr id="listHead">
                    <th>L.p.</th>
                    <th>Nazwa i plik</th>
                    <th>Typ</th>
                    <th>Rozmiar</th>
                    <th>Pokazać</th>
                    <th>Akcja</th>
                </tr>		
                <?
					$n = 1;
                    foreach ($outRow as $row)
                    {
						$rowColor = '';
						if ($n % 2 == 0){
							$rowColor = 'rowOdd';						
						}  
										
									$active_url = '';
						
						$noActive = '';
					
                        if ($row['active'] == 1) {
                            $active_url = '<a href="#" title="Ukryj" class="butActive" id="f_'.$row['id_file'].'" ><img src="template/images/icoStat1.png" alt="Ukryj" class="imgAct" /></a> ';
                        }
                        else {
                            $active_url = '<a href="#" title="Pokaż" class="butNoActive" id="f_'.$row['id_file'].'"><img src="template/images/icoStat0.png" alt="Pokaż" class="imgAct" /></a> ';
			   				$noActive = 'noactive';
                        }
                                      
						echo '<tr class="'.$rowColor.' '.$noActive.'" id="fileId_' . $row['id_file'] . '" >'
				
                            .'<td>' . ($row['pos'] + $sql_start) . '.</td>'
                            .'<td>' . $row['name'] . '<br />'
                            .'<a href="../download/' . $row['file'] . '" target="_blank" title="Podgląd w nowym oknie">' . $row['file'] . '</a></td>'
                            .'<td><span class="hide">Typ pliku: </span>' . getExt($row['file']) . '</td>'
                            .'<td><span class="hide">Rozmiar: </span>' . file_size('../download/'.$row['file']) . '</td>'
                            .'<td><span class="butIcons">' . $active_url . '</span></td>'
                            .'<td><span class="butIcons">';
        
						if ($row['pos'] > 1)
							echo '<a href="#" class="butPosTop" title="Przesuń do góry" id="fed_'.$row['id_file'].'"><img src="template/images/icoSortTop.png" alt="Przesuń do góry"></a> ';
						else
							echo '<img src="template/images/icoSortTop_.png" alt="" /> ';
						
						if ($row['pos'] < $numRows)
							echo '<a href="#" class="butPosBot" title="Przesuń na dół" id="fed_'.$row['id_file'].'"><img src="template/images/icoSortDown.png" alt="Przesuń na dół"></a> ';
						else
							echo '<img src="template/images/icoSortDown_.png" alt="" /> ';
									
                        echo '<a href="#" title="Edytuj pozycję" class="butEdit" id="fed_'.$row['id_file'].'"><img src="template/images/icoEdit.png" alt="Edytuj pozycję" class="imgAct" /></a> ';
                       
						echo '<a href="#" title="Usuń pozycję" class="butDel delLink" id="fdel_'.$row['id_file'].'"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a> ';			
					
						echo '</span></td>'
							.'</tr>';
						$n++;
                    }
                ?>
				</table>
                <div class="legend" aria-hidden="true">
                    <span class="bolder">Legenda:</span>
                    <?php
                   // include_once(CMS_TEMPL . DS . 'legend_position.php');
                    include_once(CMS_TEMPL . DS . 'legend_icons.php');
                    ?>
                </div>
			<?
			}
			?>
		</div>

			    
		    
	    </div>
            
            <div id="filesRight">
                <div class="uploadInfo">
	                <h3>Prześlij pliki z opisami</h3>
                    <ul class="navMenu">
                        <li><a href="#" class="butAddFile"><span class="icoAdd"></span><?php echo $TXT_add_file; ?></a></li>
                    </ul>
                    <br/>
                    
                    <div aria-hidden="true">
                        <h3>Prześlij wiele plików jednocześnie bez opisów</h3>
                        <p><strong>Uwaga!</strong></p>
                        <p>Pamiętaj,aby po skopiowaniu plików dodać do nich opisy.</p>                    
                        <p>Maks. rozmiar przesyłanego pliku: <span class="bolder"><?php echo $uploadMaxFilesize; ?></span>. Pliki o większym rozmiarze nie zostaną dodane do listy.</p>
                    </div>
                </div>                 
                
                <div aria-hidden="true">
	                <div id="upload"></div>
                </div>
                
                <script type="text/javascript">
                    // <![CDATA[
                    var flashvars = {};   
		  			  flashvars.mainDir = '';
                    flashvars.uploadPath = '';
                    flashvars.maxWidth = <? echo $imageConfig['maxWidth']; ?>;
                    flashvars.jpgCompression = <? echo $imageConfig['jpgCompression']; ?>;
                    flashvars.idTable = 'files'; 
                    flashvars.idPage = '<? echo $_GET['id']; ?>'; 
                    flashvars.idType = '<? echo $_SESSION['type_to_files']; ?>';
					flashvars.mini = 0;
					flashvars.miniWidth = <? echo $imageConfig['miniWidth']; ?>;
					flashvars.miniHeight = <? echo $imageConfig['miniHeight']; ?>;
					flashvars.proportional = <? echo $imageConfig['proportional']; ?>;
					flashvars.bannerTop = 0;
					flashvars.fileFilter = '<?php echo $fileFilter; ?>';
					flashvars.maxUploadSize = <?php echo $maxUploadSize; ?>;
					<?php
					if (check_login_user()){
						echo 'flashvars.uploadPermission = 1;' . "\r\n";
					}						
					?>						
		    
                    swfobject.embedSWF("upload.swf?noc=" + new Date().getTime(), "upload", "150", "45", "10.0.0", "expressInstall.swf", flashvars, {wmode:"transparent", allowScriptAccess:"always"}, {});
                    // ]]>
                </script>            
            </div>
            
            <div class="clear"></div>
        </div>
		<?php
	}
	
	if ($showEditImage){
		echo '<h2>'. $pageTitle . ': ' . $filename . '</h2>';
		?>
		<div id="editImage">
			<div id="editApp"></div>
			<script type="text/javascript">
				// <![CDATA[
				var flashvars = {};
				flashvars.panelDir = "";
				flashvars.currentPath = "../files/pl";
				flashvars.image = "<?php echo $filename; ?>";
				flashvars.maxWidth = <? echo $imageConfig['maxWidth']; ?>;
				flashvars.jpgCompression = <? echo $imageConfig['jpgCompression']; ?>;
				flashvars.idTable = 'photos';
				flashvars.bgColor = '0x<?php echo $imageConfig['bgColor']; ?>';
				flashvars.mini = 1;
				flashvars.miniWidth = <? echo $imageConfig['miniWidth']; ?>;
				flashvars.miniHeight = <? echo $imageConfig['miniHeight']; ?>;
				flashvars.proportional = <? echo $imageConfig['proportional']; ?>;
				flashvars.bannerTop = 0;
				<?php
				if (check_login_user()){
					echo 'flashvars.uploadPermission = 1;' . "\r\n";
				}						
				?>					
				swfobject.embedSWF("photo.swf?noc=" + new Date().getTime(), "editApp", "100%", "100%", "10.0.0", "expressInstall.swf", flashvars, {wmode:"opaque", allowScriptAccess:"always", bgColor:"#<?php echo $imageConfig['bgColor']; ?>"}, {});
				// ]]>
			</script>			
		</div>
		<?
	}	
	
	//echo '<div id="result"></div>';
?>
</div>