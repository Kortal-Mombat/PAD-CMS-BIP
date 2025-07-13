<div id="photosWrapper">
<script language="javascript" type="text/javascript">

	setTimeLimit();
	
    function imageEdited(file){
        var mainContent = $('#photo');
        mainContent.find('#photosWrapper').remove();
        mainContent.append('<div class="photoList">');
		var url = 'index.php?c=photos&idp=<?php echo $_GET['idp']; ?>&id=<?php echo $_GET['id']; ?>&action=editImage&filename=' + encodeURI(file);
		mainContent.find('.photoList').load(url);
    }

    function cancelEdit(){
        var mainContent = $('#photo');
        mainContent.find('#photosWrapper').remove();
        mainContent.append('<div class="photoList">');
		var url = 'index.php?c=photos&idp=<?php echo $_GET['idp']; ?>&id=<?php echo $_GET['id']; ?>';
		mainContent.find('.photoList').load(url);
	}
	
	function setUploaderHeight(uploaderHeight)
	{
		$('#upload_photos').height(uploaderHeight);
	}
		
	function reloadPage(filesCompleted)
	{
		window.onbeforeunload = null;
		$.post('ssv.php', {files: filesCompleted}, function(ret){
			$('#photo .photoList').load('index.php?c=photos&idp=<?php echo $_GET['idp']; ?>&id=<?php echo $_GET['id']; ?>&action=addFiles');
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
    actions = function(){
	
	$("#photo .butActive").click(function() { 
		var photo_id = $(this).attr('id').substr(2);
		$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+photo_id+'&action=noactive');
		return false;
	}); 
	
	$("#photo .butNoActive").click(function() { 
		var photo_id = $(this).attr('id').substr(2);
		$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+photo_id+'&action=active');
		return false;
	}); 	
	
	$("#photo .butEdit").click(function() { 
		var photo_id = $(this).attr('id').substr(4);
		$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+photo_id+'&action=edit');
         $(window).scrollTop(220);
		return false;
	}); 	

	$("#photo .butDel").click(function() { 
		var photo_id = $(this).attr('id').substr(5);
		
		qs = confirm('<? echo $MSG_del_confirm; ?>');
		if(qs)
		{
			$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+photo_id+'&action=delete');
                        $(window).scrollTop(220);
			return false;
		}		
	}); 	

	$("#photo .butPosTop").click(function() { 
		var photo_id = $(this).attr('id').substr(4);
		$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+photo_id+'&action=posTop');
		return false;
	}); 

	$("#photo .butPosBot").click(function() { 
		var photo_id = $(this).attr('id').substr(4);
		$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+photo_id+'&action=posBot');
		return false;
	}); 
	

	$("#photo .butBack").click(function() { 
		$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>');
		return false;
	});
	
	$("#photo .butEditImg").click(function() {
		var photo_id = $(this).attr('id').substr(5);
		$("#photo").load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf='+photo_id+'&action=editImg');
        $(window).scrollTop(220);
		return false;
	});
	
	
	$("#photo .butSaveAdd").click(function() { 
		var data = {
			photo_old_pos : $('#photo_old_pos').val(),
			photo_pos : $('#photo_pos').val(),
			photo_name : $('#photo_name').val(),
			photo_keywords : $('#photo_keywords').val()
		};
		$.post('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&idf=<? echo $_GET['idf']; ?>&action=edit&act=editPoz', data, function(theResponse){
			$("#photo .photoList").html(theResponse);
		});
				
		return false;
	});
	
	$("#photo .butAddFile").click(function() { 
		$('#photo .photoList').load('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&action=copy');
         $(window).scrollTop(220);
		return false;
	}); 
	
	$("#photo .butSelUploadFiles").click(function() { 
		var data = {
			filesNum : $('#filesNum').val(),
		};
		$.post('index.php?c=photos&idp=<? echo $_GET['idp']; ?>&id=<? echo $_GET['id']; ?>&action=copy&act=selUploadFiles', data, function(theResponse){
			$("#photo .photoList").html(theResponse);
		});
				
		return false;
	});
    };
    actions();
</script>

<?
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
		case 'article'	: $formURL = $PHP_SELF.'?c=articles&amp;idp='.$_GET['idp'].'&amp;id='.$_GET['id'].'&amp;action=edit&amp;filesNum=' . $_POST['filesNum'] . '&amp;act=uploadPhotos&amp;autoload=1#photo'; break;
		case 'page'		: $formURL = $PHP_SELF.'?c=page&amp;action=edit&amp;id='.$_GET['id'].'&amp;filesNum=' . $_POST['filesNum'] . '&amp;act=uploadPhotos&amp;autoload=1#photo'; break;	
	}

	if ($copyFile) 
	{
	
		echo '<form action="" method="post">'
			.'<h3>Prześlij pliki</h3>'
			.'<div class="formWrap">'
			.'<label for="filesNum">Wybierz ilość zdjęć do przesłania:</label> '
			.'<select name="filesNum" id="filesNum" size="1" style="display: inline;">';
		for ($k=1; $k<=10; $k++)
			echo '<option>'.$k.'</option>';
		echo '</select> <input type="submit" value=" Dalej " class="butSelUploadFiles"  style="display: inline;" /> </form>';
		
			if ($copyFileSel) {
				echo '<p class="txt_com">Dozwolone są pliki tylko w formacie JPG.</p>';
				
				echo '<form action="'.$formURL.'" method="post" enctype="multipart/form-data">';
				for ($i=1; $i<=$_POST['filesNum']; $i++)
				{
					echo '<label for="file'.$i.'">Wybierz zdjęcie nr '.$i.' :</label> <input name="file'.$i.'" id="file'.$i.'" type="file" size="30"><br/>';
					echo '<label for="opis'.$i.'">Opis zdjęcia nr '.$i.' :</label> <input name="opis'.$i.'" id="opis'.$i.'" type="text" size="70"><br/>';
				}
				echo '<input type="submit" value=" Prześlij na serwer " class="butUploadFiles" /> </form>';
			}	
	
		
		echo '</div>';
	}
	
	if ($showEditForm)
	{
		echo '<h2>'. $pageTitle .'</h2>';
		?>
			<form method="post" id="photoForm"  class="formEdAdd" action="" name="formEd" enctype="multipart/form-data">

                <label for="photo_pos">Kolejność: </label>
                <input type="hidden" name="photo_old_pos" id="photo_old_pos" value="<? echo $row['pos']; ?>" />
                <input type="text" name="photo_pos" id="photo_pos" size="4" maxlength="10" value="<? echo $row['pos']; ?>" /><br/>

                <label for="photo_name">Opis: </label>
                <input type="text" name="photo_name" id="photo_name" size="100" maxlength="250" value="<? echo $row['name']; ?>" /><br/>
                
                <label for="photo_keywords">Słowa kluczowe: </label>
                <input type="text" name="photo_keywords" id="photo_keywords" size="100" maxlength="250" value="<? echo $row['keywords']; ?>" class="tip" title="Słowa kluczowe służą łatwiejszemu wyszukiwaniu plików na Twojej stronie."/><br/>
				
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
                    <th>Zdjęcie</th>
                    <th>Opis</th>
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
        
                        if ($row['active'] == 1) 
						{
                            $active_url = '<a href="#" title="Ukryj" class="butActive" id="f_'.$row['id_photo'].'" ><img src="template/images/icoStat1.png" alt="Ukryj" class="imgAct" /></a> ';
                        }
                        else 
						{
                            $active_url = '<a href="#" title="Pokaż" class="butNoActive" id="f_'.$row['id_photo'].'"><img src="template/images/icoStat0.png" alt="Pokaż" class="imgAct" /></a> ';
			  				$noActive = 'noactive';
                        }
                                        
                        echo '<tr class="'.$rowColor.' '.$noActive.'" id="photoId_' . $row['id_photo'] . '" >'
                            .'<td>' . ($row['pos'] + $sql_start) . '.</td>'
                            .'<td><a href="../files/'.$lang.'/' . $row['file'] . '?noc='.time().'" rel="fancybox" target="_blank"><img src="../files/'.$lang.'/mini/' . $row['file'] . '?noc='.time().'" alt="Podgląd" class="photoMini" /></a></td>'
                            .'<td>' . $row['name'] . '</td>'
                            .'<td><span class="hide">Typ pliku: </span>' . getExt($row['file']) . '</td>'
                            .'<td><span class="hide">Rozmiar: </span>' . file_size('../files/'.$lang.'/'.$row['file']) . '</td>'
                            .'<td><span class="butIcons">' . $active_url . '</span></td>'
                            .'<td><span class="butIcons">';
        
						if ($row['pos'] > 1)
							echo '<a href="#" class="butPosTop" title="Przesuń do góry" id="fed_'.$row['id_photo'].'"><img src="template/images/icoSortTop.png" alt="Przesuń do góry"></a> ';
						else
							echo '<img src="template/images/icoSortTop_.png" alt="" /> ';
						
						if ($row['pos'] < $numRows)
							echo '<a href="#" class="butPosBot" title="Przesuń na dół" id="fed_'.$row['id_photo'].'"><img src="template/images/icoSortDown.png" alt="Przesuń na dół"></a> ';
						else
							echo '<img src="template/images/icoSortDown_.png" alt="" /> ';
		
                        echo '<a href="#" title="Edytuj pozycję" class="butEdit" id="fed_'.$row['id_photo'].'"><img src="template/images/icoEdit.png" alt="Edytuj pozycję" class="imgAct" /></a> ';
                       
						echo '<a href="#" title="Usuń pozycję" class="butDel delLink" id="fdel_'.$row['id_photo'].'"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a> ';
					
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
            
            <div id="filesRight">
                <div class="uploadInfo">
	                <h3>Prześlij zdjęcia z opisami</h3>
                    <ul class="navMenu">
                        <li><a href="#" class="butAddFile"><span class="icoAdd"></span><?php echo $TXT_add_file; ?></a></li>
                    </ul>
                    <br/>
                    
                    <div aria-hidden="true">
                        <h3>Prześlij wiele zdjęć jednocześnie bez opisów</h3>
                        <p><strong>Uwaga!</strong></p>
                        <p>Pamiętaj,aby po skopiowaniu zdjęć dodać do nich opisy.</p>                    
                        <p>Maks. rozmiar przesyłanego pliku: <span class="bolder"><?php echo $uploadMaxFilesize; ?></span>. Pliki o większym rozmiarze nie zostaną dodane do listy.</p>
                    </div>
                </div>		
    
               <div aria-hidden="true">
              	 <div id="upload_photos"></div>
               </div>

                <script type="text/javascript">
                    // <![CDATA[
					var flashvars = {};    
					flashvars.mainDir = '';
					flashvars.uploadPath = '';
					flashvars.maxWidth = <? echo $imageConfig['maxWidth']; ?>;
					flashvars.jpgCompression = <? echo $imageConfig['jpgCompression']; ?>;
					flashvars.idTable = 'photos'; 
					flashvars.idPage = '<? echo $_GET['id']; ?>'; 
					flashvars.idType = '<? echo $_SESSION['type_to_files']; ?>';
					flashvars.mini = 1;
					flashvars.miniWidth = <? echo $imageConfig['miniWidth']; ?>;
					flashvars.miniHeight = <? echo $imageConfig['miniHeight']; ?>;
					flashvars.proportional = <? echo $imageConfig['proportional']; ?>;
					flashvars.bannerTop = 0;
					flashvars.fileFilter = '<?php echo $photoFilter; ?>';
					flashvars.maxUploadSize = <?php echo $maxUploadSize; ?>;
					<?php
					if (check_login_user()){
					echo 'flashvars.uploadPermission = 1;' . "\r\n";
					}						
					?>					
		   
                    swfobject.embedSWF("upload.swf?noc=" + new Date().getTime(), "upload_photos", "150", "45", "10.0.0", "expressInstall.swf", flashvars, {wmode:"transparent", allowScriptAccess:"always"}, {});
                    // ]]>
                </script>            
     
            </div>
            
            <div class="clear"></div>

        </div>
		<?			
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
				swfobject.embedSWF("photo.swf?noc=" + new Date().getTime(), "editApp", "100%", "100%", "8.0.0", "expressInstall.swf", flashvars, {wmode:"opaque", allowScriptAccess:"always", bgColor:"#<?php echo $imageConfig['bgColor']; ?>"}, {});
				// ]]>
			</script>			
		</div>
		<?
	}
	//echo '<div id="result"></div>';
?>
</div>
