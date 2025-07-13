<script language="javascript" type="text/javascript">
    
    function setUploaderHeight(uploaderHeight)
    {
	$('#upload').height(uploaderHeight);
    }
    
    function reloadPage(filesCompleted)
    {
	window.onbeforeunload = null;
	$.post('ssv.php', {files: filesCompleted}, function(ret){
	    window.location.href = "<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&d=' . $_GET['d'] . '&action=addFiles'; ?>";
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
    
    function imageEdited(file){
	window.location.href = "<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&d=' . $_GET['d'] . '&action=editImage&image='.str_replace('.', '|', $_GET['filename']); ?>";
    }

    function cancelEdit(){
	window.location.href = "<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&d=' . $_GET['d']; ?>";
    }

</script>
<h2><?php echo $TXT_menu_files; ?></h2>
<?php
if ($showFileList){
    
?>
<ul class="navMenu">
	<li><a href="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=add'; ?>"><span class="icoAdd"></span><?php echo $TXT_add_dir; ?></a></li>
	<li><a href="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=copy'; ?>"><span class="icoAdd"></span><?php echo $TXT_add_file; ?></a></li>    
</ul>
<?php

    if ($_GET['d'] != '' && $_GET['d'] != '.')
    {
	echo '<div class="catalogue"><p>Jesteś w katalogu: <strong>' . str_replace('./', '../', $_GET['d']) . '</strong></p></div>';
    }

}

echo $message;

if ($copyFile) {

	echo '<form action="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=' . $_GET['action'] . '&amp;act=selUploadFiles" method="post">'
		.'<h3>Prześlij pliki</h3>'
		.'<div class="formWrap">'
		.'<label for="filesNum">Wybierz ilość plików do przesłania:</label> '
		.'<select name="filesNum" id="filesNum" size="1" style="display: inline;">';
	for ($k=1; $k<=10; $k++)
		echo '<option>'.$k.'</option>';
	echo '</select> <input type="submit" value=" Dalej "  style="display: inline;" /> </form>';
	
		if ($copyFileSel) {
		
			echo '<form action="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=' . $_GET['action'] . '&amp;filesNum=' . $_POST['filesNum'] . '&amp;act=uploadFiles" method="post" enctype="multipart/form-data">';
			for ($i=1; $i<=$_POST['filesNum']; $i++)
			{
				echo '<label for="file'.$i.'">Wybierz plik nr '.$i.' :</label> <input name="file'.$i.'" id="file'.$i.'" type="file" size="30"><br/>';
			}
			echo '<input type="submit" value=" Prześlij na serwer " class="butSaveAdd" /> </form>';
		}	

	
	echo '</div>';
	
}


if ($showAddForm){

?>
	<form method="post" class="formEdAdd" action="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=' . $_GET['action'] . '&amp;act=addDir'; ?>" name="formAdd" enctype="multipart/form-data">
		<h3>Utwórz katalog w katalogu: <?php echo $_GET['d']; ?></h3>
		
		<div class="formWrap">
		
			<label for="filename">Nazwa katalogu: </label>
			<input type="text" name="filename" id="filename" size="100" maxlength="250" value="<?php echo $_POST['filename']; ?>" />		
		
		</div>
		<input type="submit" value="Zapisz" class="butSave" name="save"/>
	
	</form>
<?php
}

if ($showEditForm){


?>
	<form method="post" class="formEdAdd" action="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=' . $_GET['action'] . '&amp;act=updateDir'; ?>" name="formEd" enctype="multipart/form-data">
		<h3>Zmień nazwę katalogu</h3>
		
		<div class="formWrap">
		
			<label for="filename">Nazwa katalogu: </label>
			<input type="text" name="filename" id="filename" size="100" maxlength="250" value="<?php echo $filename; ?>" />

			<input type="hidden" name="oldName" id="oldName" value="<?php echo $_GET['filename']; ?>" />
		
		</div>
		<input type="submit" value="Zapisz" class="butSave" name="save"/>	
	</form>
<?php
}

if ($showEditFileForm){

?>
	<form method="post" class="formEdAdd" action="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=' . $_GET['action'] . '&amp;act=updateFile'; ?>" name="formEd" enctype="multipart/form-data">
		<h3>Zmień nazwę pliku</h3>

		<div class="formWrap">
		
			<label for="filename">Nazwa pliku: </label>
			<input type="text" name="filename" id="filename" size="100" maxlength="250" value="<?php echo $filename; ?>" /> <strong>.<?php echo $extension; ?></strong>

			<input type="hidden" name="oldName" id="oldName" value="<?php echo $_GET['filename']; ?>" />
			
			<input type="hidden" name="extension" id="extension" value="<?php echo $extension; ?>" />
		
		</div>
		<input type="submit" value="Zapisz" class="butSave" name="save"/>	
	</form>
<?php
}
?>


<?php
if ($showFileList){
?>
<div id="filesMain">

	<div id="menuFilesHead">
		<?php echo $TXT_files_head; ?>
		<div class="menuFilesCells">
			<div class="menuFilesDate"><?php echo $TXT_files_date; ?></div>
			<div class="menuFilesSize"><?php echo $TXT_files_size; ?></div>
			<div class="menuFilesAction"><?php echo $TXT_files_actions; ?></div>
		</div>
	</div>
	
	<div id="menuFilesContent">
		<ul class="filesTree">
			<li class="firstDots">
				<a href="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d='.$back; ?>" class="parentDir">..<span class="hide"> katalog nadrzędny</span></a>
				<ul>
				<?php
				$n = 0;
				
				foreach ($arrDir as $key => $value){
					$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '/' .$value['filename'];
					echo '<li class="';
					if ($n % 2 == 0){
						echo 'rowOdd ';						
					}
					if ($n == $all-1){
						echo 'lastDots';
					}					
					echo '"';
					echo '>';
					echo '<a href="'.$url.'" class="'.$value['icon'].'"><span class="hide">Katalog </span>';
					echo $value['filename'];
					echo '</a>';
					echo '<div class="menuFilesCells">';
						echo '<div class="menuFilesDate"><span class="hide">Data: </span>'.$value['date'].'</div>';
						echo '<div class="menuFilesSize"><span class="hide">Rozmiar: </span>'.$value['size'].'</div>';
						echo '<div class="menuFilesAction">';
							echo '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=editDir&amp;filename=' . str_replace('.', '|', $value['filename']) . '" title="Edytuj nazwę"><img src="template/images/icoEdit.png" alt="Edytuj nazwę" class="imgAct" /></a> ';
							echo '<a href="javascript: confirmLink(\'' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=deleteDir&amp;filename=' . str_replace('.', '|', $value['filename']) . '\',\'' . $MSG_del_confirm . '\');" title="Usuń katalog" class="delLink"><img src="template/images/icoDel.png" alt="Usuń katalog" class="imgAct" /></a> ';
						echo '</div>';
					echo '</div>';
					echo '</li>';
					$n++;
				}
				foreach ($arrFile as $key => $value){
					echo '<li class="';
					if ($n % 2 == 0){
						echo 'rowOdd ';						
					}
					if ($n == $all-1){
						echo 'lastDots';
					}					
					echo '"';
					echo '>';
					echo '<a href="'.str_replace('\\', '/', $currentPath. DS . $value['filename'].'?noc='.time().'" class="'.$value['icon']).'" target="_blank" title="Otwarcie w nowym oknie"><span class="hide">Plik </span>';
					echo $value['filename'];
					echo '</a>';
					echo '<div class="menuFilesCells">';
					echo '<div class="menuFilesDate">'.$value['date'].'</div>';
					echo '<div class="menuFilesSize">'.$value['size'].'</div>';
					echo '<div class="menuFilesAction">';
					echo '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=editFile&amp;filename=' . str_replace('.', '|', $value['filename']) . '" title="Edytuj nazwę"><img src="template/images/icoEdit.png" alt="Edytuj nazwę" class="imgAct" /></a> ';
					echo '<a href="javascript: confirmLink(\'' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '&amp;action=deleteFile&amp;filename=' . str_replace('.', '|', $value['filename']) . '\',\'' . $MSG_del_confirm . '\');" title="Usuń plik" class="delLink"><img src="template/images/icoDel.png" alt="Usuń plik" class="imgAct" /></a> ';
					echo '</div>';						
					echo '</div>';					
					echo '</li>';
					$n++;
				}
				?>
				</ul>
			</li>
	
		</ul>
	</div>
    
			<div class="legend" aria-hidden="true">
			    <span class="bolder">Legenda:</span>

			    <?php
			    include_once(CMS_TEMPL . DS . 'legend_icons.php');
			    ?>
			</div>		    

</div>

<div id="filesRight" aria-hidden="true">
	
	<div class="uploadInfo">
    	<h3>Prześlij wiele plików jednocześnie</h3>
	    <p><span class="bolder">Uwaga!</span> Maks. rozmiar przesyłanego pliku: <span class="bolder"><?php echo $uploadMaxFilesize; ?></span>. Pliki o większym rozmiarze nie zostaną dodane do listy.</p>
    </div>
    
	<div id="upload"></div>
	<script type="text/javascript">
		// <![CDATA[
		var flashvars = {};
		
		flashvars.mainDir = "";
		flashvars.uploadPath = '<?php echo addslashes($_GET['d']); ?>';
		flashvars.maxWidth = <?php echo $imageConfig['maxWidth']; ?>;
		flashvars.jpgCompression = <?php echo $imageConfig['jpgCompression']; ?>;
		flashvars.fileFilter = '<?php echo $fileFilter; ?>';
		flashvars.maxUploadSize = <?php echo $maxUploadSize; ?>;
		<?php
		if (check_login_user()){
		    echo 'flashvars.uploadPermission = 1;' . "\r\n";
		}						
		?>		
		swfobject.embedSWF("upload.swf?noc=" + new Date().getTime(), "upload", "150", "45", "10.0.0", "expressInstall.swf", flashvars, {wmode: "transparent", allowScriptAccess: "always"}, {});
		// ]]>
	</script>	
</div>

<br class="clear" />

<?php
}
?>

<?php
if ($showEditImage){
?>
<h3>Obróbka obrazu: <?php echo str_replace('|', '.', $_GET['filename']); ?></h3>

<div id="editImage">
	<div id="editApp"></div>
	<script type="text/javascript">
		// <![CDATA[
		var flashvars = {};
		flashvars.panelDir = "";
		flashvars.currentPath = "<?php echo addslashes('../' . FILES_DIR . '/' . $_GET['d']); ?>";
		flashvars.image = "<?php echo str_replace('|', '.', $_GET['filename']); ?>";
		flashvars.maxWidth = <?php echo $imageConfig['maxWidth']; ?>;
		flashvars.jpgCompression = <?php echo $imageConfig['jpgCompression']; ?>;
		flashvars.bgColor = '0x<?php echo $imageConfig['bgColor']; ?>';
		flashvars.idTable = '';
		flashvars.mini = 0;
		flashvars.bannerTop = 0;
		<?php
		if (check_login_user()){
		    echo 'flashvars.uploadPermission = 1;' . "\r\n";
		}						
		?>		
		swfobject.embedSWF("photo.swf?noc=" + new Date().getTime(), "editApp", "100%", "100%", "10.0.0", "expressInstall.swf", flashvars, {wmode:"opaque", allowScriptAccess:"sameDomain", bgColor:"#<?php echo $imageConfig['bgColor']; ?>"}, {});
		// ]]>
	</script>
</div>
<?php
}
?>

