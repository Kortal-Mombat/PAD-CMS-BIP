<?
	echo '<h2 class="mainHeader">' . $pageName .'</h2>';
	
	echo $message;
	
	if ($showLoginForm)
	{
		include( CMS_TEMPL . DS . 'form_login.php');
	}
	
	if ($showPage)
	{
		echo $row['text'];
		
		if (! check_html_text($row['author'], '') )
		{
			echo '<div class="authorName">Autor: '.$row['author'].'</div>';
		}
		
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
				if ($row['show_date'] != '' && $row['show_date'] != '0000-00-00') {
					echo '<div class="artDate">'.$row['show_date'].'</div>';
				}
				
				if (is_array($photoLead[$row['id_art']]))
				{
					$photo = $photoLead[$row['id_art']];
					
					echo '<div class="photoWrapper">'
						.'<a href="files/'.$lang.'/'.$photo['file'].'"  rel="fancybox" title="'.$photo['name'].'" ' . $url_title . $target . '>'
							.'<span></span><img src="files/'.$lang.'/mini/'.$photo['file'].'" alt="Powiększ zdjęcie '.$photo['name'].'" /></a>'
						.'</div>';	
				}
				echo '<div class="leadTxt">'.$row['lead_text'].'</div>'
					.'<div class="clear"></div>'
					.'</div>';	
			}

			$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;id=' . $_GET['id'] . '&amp;s=';
			include (CMS_TEMPL . DS . 'pagination.php');	
			echo '</div>';			
		}			
				
		// Wypisanie plikow do pobrania
		if ($numFiles > 0)
		{	
			echo '<div class="filesWrapper">';
			echo '<h2 class="filesHead">Pliki do pobrania</h2>';
			echo '<ul>';
			foreach ($outRowFiles as $row)
            {
				$target = 'target="_blank" ';
				
				if (filesize('download/'.$row['file']) > 5000000)
				{
					$url = 'download/'.$row['file'];
				}
				else
				{
					$url = 'index.php?c=getfile&amp;id='.$row['id_file'];
				}
				if (trim($row['name']) == '')
					$name = $row['file'];
				else
					$name = $row['name'];
					
				$size = file_size('download/'.$row['file']);	
							
				echo '<li><h3><a href="'.$url.'" '.$target .'>'.$name.'</a> <span>('.$size.')</span></h3></li>';
			}
			echo '</ul>';
			echo '</div>';				
		}
		
		// Wypisanie zdjec
		if ($numPhotos > 0)
		{	
			$i = 0;
			echo '<div class="galWrapper">';
			echo '<h2 class="galHead">Galeria</h2>';
			foreach ($outRowPhotos as $row)
            {
				$i++;
				echo '<div class="photoElement">'
				    .'<div class="photoWrapperGallery">'
					.'<a href="files/'.$lang.'/'.$row['file'].'"  rel="fancybox" title="'.$row['name'].'">'
						.'<span></span><img src="files/'.$lang.'/mini/'.$row['file'].'" alt="Powiększ zdjęcie" /></a>';
				echo '</div>';
				if (! check_html_text($row['name'], '') ) {
					echo '<p>'.$row['name'].'</p>';
				}
				
				echo '</div>';
				if ($i == 2)
				{
					$i = 0;
					echo '<div class="clear"></div>';
				}						
			}
			echo '<div class="clear"></div>';
			echo '</div>';			
		}	
		
		if ($showContactForm)
		{
			include ('form_contact.php');
		}		
		
		?>
	
		<div id="metryka" class="infoWrapper">
			<h3 class="infoHead"><a href="#"><span class="hide">Rozwiń </span>Metryka</a></h3>
			<table>
				<tr><th>Podmiot udostępniający informację:</th><td><?php echo $rowPage['podmiot']; ?></td></tr>
				<tr><th>Data utworzenia:</th><td><?php echo $rowPage['show_date']; ?></td></tr>
				<tr><th>Data publikacji:</th><td><?php echo $rowPage['show_date']; ?></td></tr>
				<tr><th>Osoba sporządzająca dokument:</th><td><?php echo $rowPage['author']; ?></td></tr>
				<tr><th>Osoba wprowadzająca dokument:</th><td><?php echo $rowPage['wprowadzil']; ?></td></tr>
				<tr><th>Liczba odwiedzin:</th><td><?php echo $rowPage['counter']; ?></td></tr>
			</table>
		</div>
		
		<?php
		if ($numRegister > 0)
		{
		?>
		<div id="histZmian" class="infoWrapper">
			<h3 class="infoHead"><a href="#"><span class="hide">Rozwiń </span>Historia zmian</a></h3>
			<table>
				<tr><th>Data i godzina zmiany</th><th>Osoba zmieniająca</th><th>Opis zmiany</th><th>Poprzednia wersja</th></tr>
				<?php
					foreach ($outRowRegister as $rec )
					{
						echo '<tr>'
								.'<td>' . $rec['data_publ'] . '</td>'
								.'<td>' . $rec['os_wprow'] . '</td>' 
								.'<td>' . $rec['akcja'] . '</td>'
								.'<td><a href="index.php?c=page&amp;id=' . $rowPage['id'].'&amp;idReg='.$rec['id'].'">' . $pageName . '</a></td>'
							.'</tr>';
					}						
				?>
			</table>
		</div>
		<?php
		}

		if ($outSettings['pluginTweet'] == 'włącz')
		{
			 echo '<div class="Tweet"><iframe src="//platform.twitter.com/widgets/tweet_button.html" title="Twitter"></iframe></div>';  
		}
		
		if ($outSettings['pluginFB'] == 'włącz')
		{
			$fb_url = urlencode('http://'.$pageInfo['host'].'/index.php?c=article&id='. $_GET['id']);
			echo '<div class="FBLike"><iframe title="Facebook" src=\'http://www.facebook.com/plugins/like.php?href='.$fb_url.'&amp;layout=standard&amp;show_faces=true&amp;width=400&amp;action=like&amp;font=tahoma&amp;colorscheme='.$fbStyle.'&amp;height=32&amp;show_faces=false\'></iframe></div>';   
		}			
	}
?>

