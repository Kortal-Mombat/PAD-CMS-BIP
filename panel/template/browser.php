<?php
include_once ( CMS_TEMPL . DS . 'header.php');
$_GET['action'] = $_GET['action'] ?? '';
?>
<script language="javascript" type="text/javascript">
   
    <?php
	if ($tinyVersion == 'TinyMCE 4')
	{
	?>
	$(function(){
		$('.browserLink').live('click', function(event){
			var args    = top.tinymce.activeEditor.windowManager.getParams();
			win         = (args.window);
			input       = (args.input);
			win.document.getElementById(input).value = "<?php echo '//'.$pageInfo['host'] . '/' . str_replace( array('\\', '../', '..\\', './', '.\\'), array('/', '', '', '', ''), $currentPath) . '/'; ?>" + $(this).attr('data-n');
			top.tinymce.activeEditor.windowManager.close();
		});
	});
	<?php
	}
	?>
	
    function setUploaderHeight(uploaderHeight)
    {
		$('#upload').height(uploaderHeight);
    }

    function reloadPage(filesCompleted)
    {
		window.onbeforeunload = null;
		$.post('ssv.php', {files: filesCompleted}, function(ret){
			window.location.href = "<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&d=' . $_GET['d'] . '&action=addFiles&type=' . $_GET['type']; ?>";
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

<div id="browserMain">
    <h2>Pliki na serwerze</h2>

    <?php
    echo $message;
    ?>

    <div id="browserItems">

    <?php
	
    $n = 0;
    $m = 0;	
	
    if ($showParent){
		echo '<div class="browserItem">';
		echo '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;d='.$back.'&amp;type=' . $_GET['type'] . '" class="browserParentDir">';
		echo '<span class="browserName">Katalog wyżej</span>';
		echo '</a>';
		echo '</div>';
		$n++;
    }

    foreach ($arrDir as $key => $value){
		$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;d=' . $_GET['d'] . '/' .$value['file'] . '&amp;type=' . $_GET['type'] . '';
		if ($_GET['action'])
		{
			$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=' . $_GET['action'] . '&amp;d=' . $_GET['d'] . '/' .$value['file'] . '&amp;type=' . $_GET['type'];
		}
		echo '<div class="browserItem">';
		echo '<a href="'.$url.'" class="browserDir">';
		echo '<span class="browserName">'.$value['file'].'</span>';
		echo '</a>';
		echo '</div>';
		$n++;
		if ($n == 5){
			$n = 0;
			echo '<br class="clear" />';
		}
    }
	
    $l = 1;
	
    foreach ($arrFile as $key => $value)
	{

	if (in_array($value['ext'], $cmsConfig['photos'])){
			
	    $imgPath = '//'.$pageInfo['host'] . '/' . str_replace( array('\\', '../', '..\\', './', '.\\'), array('/', '', '', '', ''), $currentPath.'/'.$value['file']);
		
	    $onClick = 'FileBrowserDialogue.mySubmit(\''.$imgPath.'\');';
			
	    echo '<div class="browserItem">';
			
	    switch ($_GET['action']) {
		case 'adverts_img':
		    $onClick = 'opener.document.advForm.fileImage.value=\''.$imgPath.'\'; window.close(); ';
		    echo '<a href="javascript:'.$onClick.'" class="browserLink" title="' . $value['file'] . '" data-n="' . $value['file'] . '">';		    break;

		case 'adverts_flash' :
		    $onClick = 'opener.document.advForm.fileFlash.value=\''.$imgPath.'\'; window.close(); ';			echo '<a href="javascript:'.$onClick.'" class="browserLink" title="' . $value['file'] . '" data-n="' . $value['file'] . '">';			 break;
		default : 				
		    echo '<a href="javascript:'.$onClick.'" class="browserLink" title="' . $value['file'] . '" data-n="' . $value['file'] . '">';
		    break;
	    }
	    switch ($value['icon'])
	    {
		case 'fileSwfIco':
		    echo '<div id="flash_' . $l . '"></div>';
		    $swfSize = getimagesize(str_replace('\\', '/', $currentPath. DS . $value['file']));
		    $swfWidth = 110;
		    $swfHeight = round(($swfSize[1] * $swfWidth) / $swfSize[0]);
		    ?>
		    <script type="text/javascript">
		    swfobject.embedSWF('<?php echo str_replace('\\', '/', $currentPath. DS . $value['file'])?>', 'flash_<?php echo $l?>', '<?php echo $swfWidth?>', '<?php echo $swfHeight?>', '9.0.0', 'expressInstall.swf', {}, {wmode:"transparent"}, {});
		    </script>
		    <?php
		    
		    break;
		case 'fileImgIco':
		default:
		    echo '<img src="'.str_replace('\\', '/', $currentPath. DS . $value['file']).'" alt="'.$value['filename'].'" />';
		    break;
	    }
	    
	    echo '<span class="browserName">'.$value['filename'].'</span>';
	    echo '</a>';
	    echo '</div>';

	
	} 
	else
	{
	    $filePath = '//'.$pageInfo['host'] . '/' . str_replace( array('\\', '../', '..\\', './', '.\\'), array('/', '', '', '', ''), $currentPath.'/'.$value['file']);
	    
	    switch ($value['icon'])
	    {
		case 'fileXlsIco':
		    $src = 'icoDocXls.png';
		    break;
		case 'fileTxtIco':
		    $src = 'icoDocTxt.png';
		    break;
		case 'fileSwfIco':
		    $src = 'icoDocSwf.png';
		    break;
		case 'fileMovIco':
		    $src = 'icoDocMov.png';
		    break;		
		case 'fileMusIco':
		    $src = 'icoDocAud.png';
		    break;		
		case 'fileZipIco':
		    $src = 'icoDocZip.png';
		    break;		
		default:
		    $src = 'icoDocDeft.png';
		    break;
	    }	    
	    
	    switch ($_GET['type'])
	    {
		
		case 'file':
		    echo '<div class="browserItem">';
		    $onClick = 'FileBrowserDialogue.mySubmit(\''.$filePath.'\');';
		    echo '<a href="javascript:'.$onClick.'" class="browserLink" title="' . $value['file'] . '" data-n="' . $value['file'] . '">';

		    echo '<img src="template/images/' . $src . '" alt="' . $value['filename'] . '" />';
		    echo '<span class="browserName">' . $value['filename'] . '</span>';
		    echo '</a>';
		    echo '</div>';
		    break;
		case 'media':
		    echo '<div class="browserItem">';
		    $onClick = 'FileBrowserDialogue.mySubmit(\''.$filePath.'\');';
		    echo '<a href="javascript:'.$onClick.'" class="browserLink" title="' . $value['file'] . '" data-n="' . $value['file'] . '">';
		    echo '<img src="template/images/' . $src . '" alt="' . $value['filename'] . '" />';
		    echo '<span class="browserName">' . $value['filename'] . '</span>';
		    echo '</a>';
		    echo '</div>';		    
		    break;
	    }
	    
	}
	$n++;
	if ($n == 5){
	    $n = 0;
	    //echo '<br class="clear" />';
	}		
    }
	?>
	
	<br class="clear" />
	
	</div>
	
    <div id="browserUpload">
	<div class="browserUploadInfo"><span class="bolder">Uwaga!</span> Maks. rozmiar przesyłanego pliku: <span class="bolder"><?php echo $uploadMaxFilesize; ?></span>. Pliki o większym rozmiarze nie zostaną dodane do listy.</div>
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
</div>

<?php
include_once ( CMS_TEMPL . DS . 'footer.php');
?>