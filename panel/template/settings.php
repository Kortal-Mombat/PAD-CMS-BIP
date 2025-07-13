<?php
	echo '<h2>'. $pageTitle .'</h2>';

	echo $message;
?>
<form method="post"  class="formEdAdd" action="<?= $PHP_SELF.'?c=' . $_GET['c']; ?>" name="formEd" enctype="multipart/form-data">
<div id="tabs">
    <ul>
	<li><a href="#baner">Nazwa i logo serwisu</a></li>
	<li><a href="#main">Główne ustawienia</a></li>
	<li><a href="#view">Wygląd</a></li>
	<li><a href="#social">Media społecznościowe</a>
	<li><a href="#seo">Pozycjonowanie SEO</a></li>
    <li><a href="#panel">Panel</a></li>
    </ul>
    <div class="clear"></div>
    
    <div class="formWrap">
	
	<div id="baner">
	    <a name="baner"></a>
	    
	    <?php
		addEditor('logo, activeTextWww, address');
		
	    foreach ($outRow as $row)
	    {
		if ($row['tab'] == 'baner')
		{
		    $disabled = '';
		    
		    switch ($row['type'])
		    {
			case 'text':
			    echo '<label for="'. $row['id_name'] .'">'. $row['name'] .'</label>:' . addTip('settings', $row['id_name']);
			    echo '<input type="text" '.$disabled.' name="'. $row['id_name'] .'" id="'. $row['id_name'] .'" value="'. $row['attrib'] .'" size="'. ($row['values'] != '' ? $row['values'] : '100') .'"/><br/>';
			    break;
			
			case 'html':
			    echo '<label for="'. $row['id_name'] .'">'. $row['name'] .'</label>:' . addTip('settings', $row['id_name']);
			    echo '<textarea '.$disabled.' id="'. $row['id_name'] .'" name="'. $row['id_name'] .'" style="width:98%; height: 200px">'. $row['attrib'] .'</textarea><br/>';
			    break;
			
			case 'select' :
				echo '<label for="' . $row['id_name'] . '" id="' . $row['id_name'] . '">' . $row['name'] . '</label>:'. addTip('settings', $row['id_name']);;

				$selectOptions = $$row['values'];

				echo '<select '.$disabled.' name="' . $row['id_name'] . '" id="' . $row['id_name'] . '">';
				foreach ($selectOptions as $key => $value)
				{
				    $selected = '';
				    if ($value == trim($row['attrib']))
				    {
					    $selected = ' selected="selected"';
				    }
				    echo '<option value="' . $value . '"' . $selected . '>';
				    echo $key;
				    echo '</option>';
				}
				echo '</select>';
			    break;			
		    }
		}
	    }
	    ?>	    
	</div>
	
	<div id="main">
	    <a name="main"></a>
	    <?php
	    foreach ($outRow as $row)
	    {
		if ($row['tab'] == 'main')
		{
		    switch ($row['type'])
		    {
			case 'text':

				// To ustawienie na razie niepotrzebne
			    if ($row['id_name'] != 'emailFormContact')
				{			    
					echo '<label for="'. $row['id_name'] .'">'. $row['name'] .'</label>:' . addTip('settings', $row['id_name']);
					echo '<input type="text" name="'. $row['id_name'] .'" id="'. $row['id_name'] .'" value="'. $row['attrib'] .'" size="'. ($row['values'] != '' ? $row['values'] : '100') .'"/><br/>';
				}
			    break;
				

			case 'html':
			    echo '<label for="'. $row['id_name'] .'">'. $row['name'] .'</label>:';
			    echo '<textarea '.$disabled.' id="'. $row['id_name'] .'" name="'. $row['id_name'] .'" style="width:98%; height: 200px">'. $row['attrib'] .'</textarea><br/>';
			    break;
								
			case 'radio' : 
			    echo '<div class="label_txt">'. $row['name'] .'</div>:<br />';
			    $radioList = explode(',', $row['values']);

			    foreach ($radioList as $key => $value)
			    {
				$checked = '';
				if ($value == trim($row['attrib'])) {
					$checked = 'checked="checked"';
				}
				echo '<input type="radio" '.$disabled.' name="'. $row['id_name'] .'" id="'. $row['id_name'] .'_'. $key .'" value="'. $value .'" '.$checked.' />';
				echo '<label for="'. $row['id_name'] .'_'. $key .'" class="checkInput smallWidth">'. $value .'</label>';
			    }
			    echo '<br />';
			    break;				
		    }
		}
	    }
	    ?>
	</div>
	
	<div id="view">
	    <a name="view"></a>
	    <?php
	    foreach ($outRow as $row)
	    {
		if ($row['tab'] == 'view')
		{
		    $disabled = '';
		    
		    switch ($row['type'])
		    {
			case 'text':
				echo '<label for="'. $row['id_name'] .'">'. $row['name'] .'</label>:' . addTip('settings', $row['id_name']);
				echo '<input type="text" '.$disabled.' name="'. $row['id_name'] .'" id="'. $row['id_name'] .'" value="'. $row['attrib'] .'" size="'. ($row['values'] != '' ? $row['values'] : '100') .'"/><br/>';
			    break;
			
			case 'select' :
				echo '<label for="' . $row['id_name'] . '" id="' . $row['id_name'] . '">' . $row['name'] . '</label>:' . addTip('settings', $row['id_name']);

				$selectOptions = $$row['values'];

				echo '<select '.$disabled.' name="' . $row['id_name'] . '" id="' . $row['id_name'] . '">';
				foreach ($selectOptions as $key => $value)
				{
				    $selected = '';
				    if ($value == trim($row['attrib']))
				    {
					    $selected = ' selected="selected"';
				    }
				    echo '<option value="' . $value . '"' . $selected . '>';
				    echo $key;
				    echo '</option>';
				}
				echo '</select>';
			    break;
			    
			case 'radio' : 
			    echo '<div class="label_txt">'. $row['name'] .'</div>:' . addTip('settings', $row['id_name']) . '<br />';
			    $radioList = explode(',', $row['values']);

			    foreach ($radioList as $key => $value)
			    {
				$checked = '';
				if ($value == trim($row['attrib'])) {
					$checked = 'checked="checked"';
				}
				echo '<input type="radio" '.$disabled.' name="'. $row['id_name'] .'" id="'. $row['id_name'] .'_'. $key .'" value="'. $value .'" '.$checked.' />';
				echo '<label for="'. $row['id_name'] .'_'. $key .'" class="checkInput smallWidth">'. $value .'</label> ';
			    }
			    break;			    

			case 'html':
			    echo '<label for="'. $row['id_name'] .'">'. $row['name'] .'</label>:' . addTip('settings', $row['id_name']);
			    echo '<textarea '.$disabled.' id="'. $row['id_name'] .'" name="'. $row['id_name'] .'" style="width:98%; height: 200px">'. $row['attrib'] .'</textarea><br/>';
			    break;		
		    }
		}
	    }
	    ?>	    
	</div>
	
	<div id="social">
	    <a name="social"></a>
	    <?php
	    foreach ($outRow as $row)
	    {
		if ($row['tab'] == 'social')
		{
		    $disabled = '';
		    
		    switch ($row['type'])
		    {
			case 'radio' : 
			    echo '<fieldset class="changeTitle"><legend>'. $row['name'] .':</legend>';
			    $radioList = explode(',', $row['values']);

			    foreach ($radioList as $key => $value)
			    {
					$checked = '';
					if ($value == trim($row['attrib'])) {
						$checked = 'checked="checked"';
					}
					echo '<input type="radio" '.$disabled.' name="'. $row['id_name'] .'" id="'. $row['id_name'] .'_'. $key .'" value="'. $value .'" '.$checked.' />';
					echo '<label for="'. $row['id_name'] .'_'. $key .'" class="checkInput smallWidth">'. $value .'</label>';
			    }
			    echo '</fieldset>';
			    break;
		    }
		}
	    }
	    ?>
	</div>	

	<div id="seo">
	    <a name="seo"></a>
	    <p>Elementy ułatwiające pozycjonowanie strony w wyszukiwarkach, np. Google.</p>
	    <?php
	    foreach ($outRow as $row)
	    {
		if ($row['tab'] == 'seo')
		{
		    $disabled = '';
		    
		    switch ($row['type'])
		    {
			case 'text':
			    echo '<label for="'. $row['id_name'] .'">'. $row['name'] .'</label>:' . addTip('settings', $row['id_name']);
			    echo '<input type="text" '.$disabled.' name="'. $row['id_name'] .'" id="'. $row['id_name'] .'" value="'. $row['attrib'] .'" size="'. ($row['values'] != '' ? $row['values'] : '100') .'"/><br/>';
			    break;
		    }
		}
	    }
	    ?>
	</div>
    
	<div id="panel">
	    <a name="panel"></a>
	    <?php
	    foreach ($outRow as $row)
	    {
		if ($row['tab'] == 'panel')
		{
		    $disabled = '';
		    
		    switch ($row['type'])
		    {
			case 'radio' : 
			    echo '<div class="label_txt">'. $row['name'] .'</div>:<br />';
			    $radioList = explode(',', $row['values']);

			    foreach ($radioList as $key => $value)
			    {
					$checked = '';
					if ($value == trim($row['attrib'])) {
						$checked = 'checked="checked"';
				}
				echo '<input type="radio" '.$disabled.' name="'. $row['id_name'] .'" id="'. $row['id_name'] .'_'. $key .'" value="'. $value .'" '.$checked.' />';
				echo '<label for="'. $row['id_name'] .'_'. $key .'" class="checkInput smallWidth">'. $value .'</label>';
				echo '<br />';
			    }
			   
			    break;
		    }
		}
	    }
	    ?>
	</div>	    

    </div>
                                                                         
</div>
    
<input type="submit" value="Zapisz" class="butSave" name="save"/>
</form>

