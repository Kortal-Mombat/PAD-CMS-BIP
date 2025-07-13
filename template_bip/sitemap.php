<h2><?php echo $pageName; ?></h2>

<div class="txtWrapper">
    
<?php
	foreach ($menuType as $k) 
	{
		if ($k['active'] == 1)
		{
			echo '<h3>'.$k['name'].'</h3>';
			get_menu_tree ($k['menutype'], 0, 0, 'sitemap');
		}
	}		
?>
</div>