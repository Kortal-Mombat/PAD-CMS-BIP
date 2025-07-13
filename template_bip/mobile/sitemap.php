<h2 class="mainHeader"><? echo $pageName; ?></h2>

<?
	foreach ($menuType as $k) 
	{
		if ($k['active'] == 1)
		{
			echo '<h3>'.$k['name'].'</h3>';
			get_menu_tree ($k['menutype'], 0, 0, 'sitemap');
		}
	}		
?>