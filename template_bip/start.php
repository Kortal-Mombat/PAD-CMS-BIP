<?php
	echo '<h2 class="lastNews">Ostatnie aktualizacje</h2>';
	
	echo '<div class="txtWrapper">';
	echo $message;

	// Wypisanie artykulow
	if ($numArticles > 0)
	{	
		$i = 0;
		echo '<div class="articleWrapper">';
		echo '<h2 class="hide">Artykuły</h2>';
		foreach ($outRowArticles as $row)
		{
			$highlight = $url = $target = $url_title = $protect = '';
			
			if ($row['protected'] == 1)
			{
				$protect = '<span class="protectedPage"></span>';
				$url_title = ' title="' . $TXT['protected_page'] . '"';
			}				
			
			if (trim($row['ext_url']) != '')
			{
				if ($row['new_window'] == '1')
				{
					$target = ' target="_blank"';
				}	
				$url_title = ' title="' . $TXT['new_window'] . '"';
				$url = ref_replace($row['ext_url']);					
			}
			else
			{
				$url = 'index.php?c=article&amp;id=' . $row['id_art'];
			}	
						
			if ($row['highlight'] == 1) {
				$highlight = ' highlightArt';
			}
			
			$row['show_date'] = substr($row['show_date'], 0, 10);
			
			echo '<div class="article' . $highlight . '">'
				.'<h3><a href="' . $url . '" ' . $url_title . $target .'>' . $row['name'] . $protect . '</a></h3>';
			
			if (is_array($photoLead[$row['id_art']]))
			{
				$photo = $photoLead[$row['id_art']];
				
				echo '<div class="photoWrapper">'
					//.'<a href="files/'.$lang.'/'.$photo['file'].'"  rel="fancybox" title="'.$photo['name'].'" ' . $url_title . $target . '>'
					.'<a href="' . $url . '" ' . $url_title . $target .'>'
						.'<span class="zoom"></span><span class="bgHover"></span><img src="files/'.$lang.'/mini/'.$photo['file'].'" alt="Powiększ zdjęcie '.$photo['name'].'" /></a>'
					.'</div>';	
			}
			echo '<div class="leadTxt">'.$row['lead_text'].'</div>';
			if ($row['show_date'] != '' && $row['show_date'] != '0000-00-00') {
				echo '<div class="artDate">'.showDateMonth($row['show_date']).'</div>';
			}
			echo '<a href="' . $url . '" ' . $url_title . $target .' class="more" tabindex="-1">Czytaj więcej<span class="hide"> o: ' . $row['name'] . $protect . '</span></a>';
			echo '<div class="clear"></div>'
				.'</div>';	
		}

		$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;id=' . $_GET['id'] . '&amp;s=';
		include (CMS_TEMPL . DS . 'pagination.php');	
		echo '</div>';			
	}
	
	echo '</div>';	
?>	