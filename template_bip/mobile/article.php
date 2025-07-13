<?php
	echo '<h2 class="mainHeader">' . ($pageName ?? '') .'</h2>';
	
	echo $message;

	if ($showLoginForm)
	{
		include( CMS_TEMPL . DS . 'form_login.php');
	}
		
	if ($showArticle)
	{
		echo $article['text'];
		
		if (! check_html_text($article['author'], '') )
		{
			echo '<div class="authorName">Autor: '.$article['author'].'</div>';
		}
				
		// Wypisanie plikow do pobrania
		if ($numFiles > 0)
		{	
			echo '<div class="filesWrapper">';
			echo '<h3 class="filesHead">Załączniki</h3>';
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
							
				echo '<li><a href="'.$url.'" '.$target .'>'.$name.'</a> <span>('.$size.')</span></li>';
			}
			echo '</ul>';
			echo '</div>';				
		}
		
		// Wypisanie zdjec
		if ($numPhotos > 0)
		{	
			$i = 0;
			echo '<div class="galWrapper">';
			echo '<h3 class="galHead">Galeria</h3>';
			foreach ($outRowPhotos as $row)
            {
				$i++;
				echo '<div class="photoElement">'
				    .'<div class="photoWrapperGallery">'
					.'<a href="files/'.$lang.'/'.$row['file'].'"  rel="fancybox" title="'.$row['name'].'">'
						.'<img src="files/'.$lang.'/mini/'.$row['file'].'" alt="Powiększ zdjęcie" /></a>';
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

		?>
		<div id="metryka" class="infoWrapper">
			<h3 class="infoHead"><a href="#"><span class="hide">Rozwiń </span>Metryka</a></h3>
			<table>
				<tr><th>Podmiot udostępniający informację:</th><td><?php echo $article['podmiot']; ?></td></tr>
				<tr><th>Data utworzenia:</th><td><?php echo $article['show_date']; ?></td></tr>
				<tr><th>Data publikacji:</th><td><?php echo $article['show_date']; ?></td></tr>
				<tr><th>Osoba sporządzająca dokument:</th><td><?php echo $article['author']; ?></td></tr>
				<tr><th>Osoba wprowadzająca dokument:</th><td><?php echo $article['wprowadzil']; ?></td></tr>
				<tr><th>Liczba odwiedzin:</th><td><?php echo $article['counter']; ?></td></tr>
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
								.'<td><a href="index.php?c=article&amp;id=' . $article['id_art'].'&amp;idReg='.$rec['id'].'">' . $pageName . '</a></td>'
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
			$fb_url = urlencode('//'.$pageInfo['host'].'/index.php?c=article&id='. $_GET['id']);
			echo '<div class="FBLike"><iframe  title="Facebook" src=\'http://www.facebook.com/plugins/like.php?href='.$fb_url.'&amp;layout=standard&amp;show_faces=true&amp;width=400&amp;action=like&amp;font=tahoma&amp;colorscheme='.$fbStyle.'&amp;height=32&amp;show_faces=false\' ></iframe></div>';   
		}			
	
	}


?>

