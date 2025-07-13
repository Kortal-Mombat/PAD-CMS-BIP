<?
	echo '<h2>' . $pageName .'</h2>';
	
	?>
	<ul class="printers">
    	<li><a href="<?php echo $PHP_SELF . '?c='.$_GET['c'].'&amp;id=' . $_GET['id']; ?>&amp;print=1" target="_blank" ><img src="<?php echo $templateDir; ?>/images/butPrint.png" alt="<?php echo $TXT['print'];?>"/></a></li>
        <li><a href="<?php echo $PHP_SELF . '?c='.$_GET['c'].'&amp;id=' . $_GET['id']; ?>&amp;pdf=1" target="_blank" ><img src="<?php echo $templateDir; ?>/images/butPDF.png" alt="<?php echo $TXT['print_pdf'];?>"/></a></li>
    </ul>
	<?php
		
	echo '<div class="txtWrapper">';
	
	echo $message;

	if ($showLoginForm)
	{
		include( CMS_TEMPL . DS . 'form_login.php');
	}
		
	if ($showArticle)
	{
		if ($article['show_date'] != '' && $article['show_date'] != '0000-00-00') {
			echo '<div class="artDate">'.showDateMonth($article['show_date']).'</div>';
		}	    
				
		echo '<div class="leadArticle">' . $article['lead_text'] . '</div>';
		
		echo $article['text'];
		
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
			echo '<ul class="galList">';
			foreach ($outRowPhotos as $row)
            {
				$i++;
				echo '<li><div class="photoWrapper">'
					.'<a href="files/'.$lang.'/'.$row['file'].'" data-rel="fancybox" title="'.$row['name'].'">'
						.'<span class="zoom"></span><span class="bgHover"></span><img src="files/'.$lang.'/mini/'.$row['file'].'" alt="Powiększ zdjęcie '.$row['name'].'" />'
					.'</a>';
					
				if (! check_html_text($row['name'], '') ) {
					echo '<p class="name">'.$row['name'].'</p>';
				}
				echo '</div>';	
				echo '</li>';					
			}
			echo '</ul>';
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
			$fb_url = urlencode('http://'.$pageInfo['host'].'/index.php?c=article&id='. $_GET['id']);
			echo '<div class="FBLike"><iframe  title="Facebook" src=\'http://www.facebook.com/plugins/like.php?href='.$fb_url.'&amp;layout=standard&amp;show_faces=true&amp;width=400&amp;action=like&amp;font=tahoma&amp;colorscheme='.$fbStyle.'&amp;height=32&amp;show_faces=false\' ></iframe></div>';   
		}			
	}
?>
</div>