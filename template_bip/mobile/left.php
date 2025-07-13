<div id="menuCol">
    
    <a name="skip_mg" id="skip_mg"></a>

	<?php
	    echo '<div class="menuSliderBg">';
	    echo '<h2 class="menuHeader"><a href="#" class="menuSlider">Menu dodatkowe</a></h2>';
        
	    echo '<div class="menuWrapper">';
            get_menu_tree ('mg');
	    echo '</div>';
	    
	    echo '</div>';
	    
	    
        
	    //dynamiczne menu - jesli takie istnieja
	    foreach ($menuType as $dynMenu)
	    {
		    if ($dynMenu['menutype'] != 'mg' && $dynMenu['menutype'] != 'tm')
		    {
			    if ($dynMenu['active'] == 1)
			    {
				echo '<div class="menuSliderBg">';
				    echo '<h2 class="menuHeader"><a href="#" class="menuSlider">'.$dynMenu['name'].'</a></h2>';
				    echo '<div class="menuWrapper">';
				    get_menu_tree ($dynMenu['menutype']);
				    echo '</div>';
				echo '</div>';
			    } 
		    }
	    }
	    
	?>
    <div class="bgMenuBot"></div>
</div>