<div id="menuWrapper" role="navigation">
	<h2 class="hide"><a id="skip_mm">Menu główne</a></h2>
	<?php
	$_SESSION['mp'] = $_SESSION['mp'] ?? '';
	if ($numRowsMenu>0)
	{
		echo '<ul>';
		foreach ($outRowMenu as $menuRow)
		{
			if ($menuRow['ref'] == 0)
			{
				$url = '';
				$urlClass = '';
				
				$addTourl = '&mp='.$menuRow['id_mp'];
				
				if ($_SESSION['mp'] == $menuRow['id_mp'])
				{
					$urlClass = ' class="menuSel" ';
				}
								
				if ($menuRow['controler'] == '-')
				{
					$url = '<a href="'.$PHP_SELF. '?' . ref_replace($addTourl) .'" '.$urlClass.'>'.$menuRow['name'].'</a>';
				}
				else if ($menuRow['controler'] == '')
				{
					$url = '<span>'.$menuRow['name'].'</span>';
				}
				else
				{
					
					if ($menuRow['link'] !='')
					{
						$addTourl .= '&' . $menuRow['link'];
					}

					$url = '<a href="'.$PHP_SELF.'?c='. $menuRow['controler'] . ref_replace($addTourl) .'" '.$urlClass.'>'.$menuRow['name'].'</a>';
				}
				
				if ( $_SESSION['userData']['type'] == 'admin' || in_array($menuRow['id_mp'], $_SESSION['userData']['privMenu'] ) ) 
				{
					echo '<li>' . $url ;
					//get_submenu($outRowMenu, $menuRow['id_mp'], $_GET['c']);
					echo '</li>';	
				}		
			}
		}	
		
		echo '<li class="lastLi"></li>';	
		echo '</ul>';		
	}

	?>	
 
</div>