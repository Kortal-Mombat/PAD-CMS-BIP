<a name="top" id="top"></a>

<ul class="skipLinks">
    <li><a href="#skip_tm"><?= $TXT['skiplink_tm']; ?></a></li> 
    <li><a href="#skip_mg"><?= $TXT['skiplink_mg']; ?></a></li> 
    <li><a href="#skip_txt"><?= $TXT['skiplink_txt']; ?></a></li>
    <li><a href="#skip_srch"><?= $TXT['skiplink_srch']; ?></a></li>
    <li><a href="index.php?p=map"><?= $TXT['site_map']; ?></a></li>
</ul>	
    
<div id="popup"></div>
<div class="tips"><div class="cloudR"></div></div>

<div id="headerWrapper">
	<div id="header">
		<div id="headerName"><h1 id="mainHead"><?php echo str_replace(array(' im', ' w'), array(' <br />im', '<br />w'), $pageInfo['name']); ?></h1></div>
		<div id="headerAddress"><?php echo $headerAddress; ?></div>
		<div id="headerGraph"></div>
       
        <?php
        	 include_once ( CMS_TEMPL . DS . 'toolbar.php');
		?>
		
	<div id="templateMenuSep"></div>
        <?php 
			// jesli zalogowany to doklej formatke zalogowanego
			if (isset($showProtected) || isset($forumLogged))
			{
				include( CMS_TEMPL . DS . 'user_info.php');
			}

		?>
	
        <div class="menuSliderBg">
	    <a name="skip_tm" id="skip_tm"></a>
            <h2 class="menuHeader"><a href="#" class="menuSlider">Menu główne</a></h2>
	    <div class="menuWrapper">
		<?php
                get_menu_tree ('tm');
		?>
	    </div>
	        
        </div>
	</div>
</div>