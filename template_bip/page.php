<?php
	if (!isset($showPage) && !isset($showLoginForm)) {
		header('Location: /error-404');
		exit;
	}
	echo '<h2>' . ($pageName ?? '') .'</h2>';
	?>
	<ul class="printers">
    	<li><a href="<?php echo $PHP_SELF . '?c='.$_GET['c'].'&amp;id=' . $_GET['id']; ?>&amp;print=1" target="_blank" ><img src="/<?php echo $templateDir; ?>/images/butPrint.png" alt="<?php echo $TXT['print'];?>"/></a></li>
        <li><a href="<?php echo $PHP_SELF . '?c='.$_GET['c'].'&amp;id=' . $_GET['id']; ?>&amp;pdf=1" target="_blank" ><img src="/<?php echo $templateDir; ?>/images/butPDF.png" alt="<?php echo $TXT['print_pdf'];?>"/></a></li>
    </ul>
	<?php
		
	echo '<div class="txtWrapper">';
	
	echo $message;
	
	if ($showLoginForm)
	{
		include( CMS_TEMPL . DS . 'form_login.php');
	}
	
	if (isset($showPage) && $showPage)
	{
		if (($numSubmenu ?? 0) > 0)
		{
			echo '<ul class="submenu">';
			foreach ($submenu as $sm)
			{
				$url_title = '';
				$target = '';
				if (trim($sm['ext_url']) != '')
				{
					if ($sm['new_window'] == '1')
					{
						$target = ' target="_blank"';
						$url_title = ' title="' . $TXT['new_window'] . '"';
					}	
					
					$url = ref_replace($sm['ext_url']);					
				} 
				else
				{
					if ($sm['url_name'] == '')
					{
						$url = 'index.php?c=page&amp;id='. $sm['id'];
					} 
					else
					{
						$url = 'p,' . $sm['id'] . ',' . trans_url_name($sm['url_name']);
					}
				}				
				echo '<li><a href="'.$url.'" ' . $url_title . $target . '>'.$sm['name'].'</a></li>';
			}
			echo '</ul>';
		}
				
		echo ($rowPage['text'] ?? '');
		
		// Wypisanie artykulow
		if ($numArticles > 0)
		{	
			$i = 0;
			echo '<div class="articleWrapper">';
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
					if ($row['url_name'] == '')
					{
						$url = 'index.php?c=article&amp;id=' . $row['id_art'];
					} 
					else
					{
						$url = 'art,' . $row['id_art'] . ',' . trans_url_name($row['url_name']);
					}					
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
						//.'<a href="files/'.$lang.'/'.$photo['file'].'"  rel="fancybox" title="'.$photo['name'].'" ' . $url_title . $target . '>'
						.'<a href="' . $url . '" ' . $url_title . $target .'>'
							.'<span class="zoom"></span><span class="bgHover"></span><img src="files/'.$lang.'/mini/'.$photo['file'].'" alt="Powiększ zdjęcie '.$photo['name'].'" /></a>'
						.'</div>';	
				}
				echo '<div class="leadTxt">'.$row['lead_text'];
				if (!check_html_text($row['text']))
				{
					echo '<a href="' . $url . '" ' . $url_title . $target .' class="more">Czytaj więcej<span class="hide"> o: ' . $row['name'] . $protect . '</span></a>';
				}
				echo '</div>'
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
					.'<a href="files/'.$lang.'/'.$row['file'].'" data-rel="fancybox"  title="'.$row['name'].'">'
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
		
		if (isset($showContactForm) && $showContactForm)
		{
			include ('form_contact.php');
		}		
		
		if(isset($rowPage)) {
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
		}
		if (($numRegister ?? 0) > 0)
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
			$fb_url = urlencode('//'.$pageInfo['host'].'/index.php?c=page&id='. $_GET['id']);
			echo '<div class="FBLike"><iframe title="Facebook" src=\'http://www.facebook.com/plugins/like.php?href='.$fb_url.'&amp;layout=standard&amp;show_faces=true&amp;width=400&amp;action=like&amp;font=tahoma&amp;colorscheme='.$fbStyle.'&amp;height=32&amp;show_faces=false\'></iframe></div>';   
		}	
	}
?>					
	</div>