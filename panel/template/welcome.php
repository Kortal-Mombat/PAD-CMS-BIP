<h2><?php echo $pageTitle; ?></h2>

<?php echo $message; ?>

<form method="post" action="<?php echo $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=update'; ?>">
    <fieldset class="noBorder">
	<legend class="hide"></legend>
	
	<div class="formWrap">                    
	    <a name="txt"></a>
	
	    <label for="active">Pokazać?: </label><?php echo addTip('welcome', 'show'); ?><br />
	    <input type="checkbox" name="active" id="active" <?php echo $checked; ?>/><br />
		
	    <label for="text">Treść: </label>
	    <textarea id="text" name="text" style="width:98%; height: 450px"><?php echo $r['text']; ?></textarea>
	    <?php echo addEditor('text'); ?>
	    
	    <input type="hidden" name="id" value="<?php echo $r['id']; ?>" />
	    
	    <p class="date">Data ostatniej aktualizacji: <span><?php echo $r['modified_date']; ?></span></p>
	</div>
	
	<input type="submit" value="Zapisz" class="butSave" name="save"/>
    
    </fieldset>
</form>