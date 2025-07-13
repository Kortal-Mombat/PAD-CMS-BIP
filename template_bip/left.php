<div id="menuCol">
    <div id="menuWrapper">
        
        <div class="menu" role="navigation">
            <a id="skip_tm" tabindex="-1"></a>
            <h2 class="tm">Menu podmiotowe</h2>
            <div class="menuBot"></div>
            <?
                get_menu_tree ('tm');
            ?>	
		</div>

		<div class="menu" role="navigation">
            <a id="skip_mg" tabindex="-1"></a>       
            <h2 class="mg">Menu przedmiotowe</h2>
            <div class="menuBot"></div>            
            <?php
                get_menu_tree ('mg');
            ?>
		</div>
        
    </div>
    
    <div id="contactAddress" role="complementary">
        <h2>Dane kontaktowe</h2>
		<?php 
            echo $headerAddress; 
            if ($pageInfo['email'] != '')
            {
                echo '<p>E-mail: <a href="mailto:'.$pageInfo['email'].'">'.$pageInfo['email'].'</a></p>';
            }			
        ?>
    </div>    

	<div id="counterWrapper">
	    <p>Odwiedziny: <span><?php echo my_counter(); ?></span></p>
    </div>
    

</div>