<h2 class="mainHeader"><?= $pageName; ?></h2>

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