<h2><?php echo $pageTitle; ?></h2>

<p>Aby uzyskać pomoc, wyślij maila na poniższy adres lub skorzystaj z formularza.</p>
<p class="helpEmail"><a href="mailto:<?php echo $cmsConfig['help_email']; ?>"><?php echo $cmsConfig['help_email']; ?></a></p>

<?php
	echo $message;
?>
<form method="post"  class="formEdAdd" action="<? echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=send'; ?>" name="formSend" enctype="multipart/form-data">
<div id="tabs">
    <ul>
        <li><a href="#txt">Treść</a></li>
    </ul>
    <div class="clear"></div>
    <div class="formWrap">                    
        <div id="txt">
            <a name="txt"></a>						
            
            <label for="subject">Temat: </label>
            <input type="text" name="subject" id="subject" size="100" maxlength="250" value="<?php echo $subject; ?>" /><br/>
            
            <label for="lead_text">Treść wiadomości: </label>
            <textarea id="text" name="text" style="width:98%; height: 250px"><?php echo $text; ?></textarea><br/>	
        </div>
    </div>                                                                       
</div>
    
<input type="submit" value="Wyślij" class="butSave" name="save"/>
</form>	     
