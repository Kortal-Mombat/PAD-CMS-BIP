<?php
	if($_COOKIE['cookieOK'] != 1)
	{	
	?>
		<div id="cookiesMsgWrp">
        	<div id="cookiesMsg">
		    	<p>Strona korzysta z plików cookies w celu realizacji usług i zgodnie z <a href="p,8,polityka-prywatnosci">Polityką prywatności</a>. Możesz określić warunki przechowywania lub dostępu do plików cookies w Twojej przeglądarce. <a href="#" id="cclose">Zamknij</a></p>
			</div>
        </div>
    <?php
	}
?>

<a id="top" tabindex="-1"></a>

<ul class="skipLinks">
    <li><a href="#skip_tm"><? echo $TXT['skiplink_tm']; ?></a></li> 
    <li><a href="#skip_mg"><? echo $TXT['skiplink_mg']; ?></a></li> 
    <li><a href="#skip_srch"><? echo $TXT['skiplink_srch']; ?></a></li>
    <li><a href="#skip_txt"><? echo $TXT['skiplink_txt']; ?></a></li>
</ul>	
    
<div id="popup"></div>

<div id="headerWrapper">

	<div id="header">
		
        <a href="index.php" class="logoBIP"><img src="<?php echo $templateDir; ?>/images/logoBIP.png" alt="Przejdź do strony głównej"/></a>
		
        <div id="headerName" role="banner">
        	<h1 id="mainHead"><?php echo word_wrap( array(' im', ' w'), $pageInfo['name']); ?><span class="hide"> - <?php echo $pageName; ?></span></h1>
        	<div id="headerLogo"><?php echo $pageInfo['logo']; ?></div>
        </div>

        <div id="fontWrapper">
            <div id="fonts">
            <?php
            echo '<p>'.$TXT['fontsize'].':</p>';
                echo '<ul>';
                echo '<li><a href="ch_style.php?style=0" class="fontDefault" title="'.$TXT['fontsize'].' '.$TXT['fontsize_d'].'"><img src="'.$templateDir.'/images/fontDefault.png" alt="'.$TXT['fontsize'].' '.$TXT['fontsize_d'].'"/></a></li>';
                echo '<li><a href="ch_style.php?style=r1" class="fontBigger" title="'.$TXT['fontsize'].' '.$TXT['fontsize_m'].'"><img src="'.$templateDir.'/images/fontBig.png" alt="'.$TXT['fontsize'].' '.$TXT['fontsize_m'].'"/></a></li>';
                echo '<li><a href="ch_style.php?style=r2" class="fontBig" title="'.$TXT['fontsize'].' '.$TXT['fontsize_b'].'"><img src="'.$templateDir.'/images/fontBigger.png" alt="'.$TXT['fontsize'].' '.$TXT['fontsize_b'].'"/></a></li>';
                
            if ($_SESSION['contr'] == 0)
                $set_contrast = 1;
            else
                $set_contrast = 0;
            echo '<li class="fontContrast"><p>Kontrast</p> <a href="ch_style.php?contr='.$set_contrast.'" title="'.$TXT['font_contrast'].'"><img src="'.$templateDir.'/images/icoContrast.png" alt="'.$TXT['font_contrast'].'"/></a></li>';
                echo '</ul>'; 
            ?> 
            <div class="clear"></div>
            </div>
        </div>
        
        <a href="mobile" id="mobileVer"><span class="hide">Wersja </span>Mobilna<img src="<?php echo $templateDir; ?>/images/icoMobile.png" alt="" /></a>
                
        <div id="infoWrapper">
        	<?php 
				$k_d_tyg = date("w");
				$k_miesiac = date("m");
				$k_dzien =  date("j");
				$k_imieniny = file ("calendar/".$k_miesiac.".txt");	
				$arIm = explode (",",$k_imieniny[$k_dzien-1]);
				
				$dayName = $arrWeek[$k_d_tyg-1]['long'];
				if ($k_d_tyg == 0)
				{
					$dayName = 'Niedziela';
				}
				
				echo '<p><span class="weekDay">'.$dayName.', </span>'.showDateMonth($shortDate).' '
					.'<span class="nameDay">'.$arIm[0].', '.$arIm[1] .'</span>'
					.'</p>';			
			?>
        </div>        
         
        <div id="linksWrapper">
        	<ul>
                <li><a href="http://bip.gov.pl" title="<? echo $TXT['but_bip']; ?>" class="bipLink"><img src="<?php echo $templateDir; ?>/images/logoBIPTop.png" alt="<? echo $TXT['but_bip_short']; ?>" /></a></li>
                <?php 
				if ($pageInfo['www'] != '')
				{
					?>
					<li><a href="<?php echo $pageInfo['www']; ?>" class="topLink icoHome"><? echo $TXT['but_start']; ?> podmiotu</a></li>
					<?php
				}
				?>
                
                <li><a href="mapa_strony" class="topLink icoSitemap"><? echo $TXT['site_map']; ?></a></li>
            </ul>
        </div>
        
     	<a id="skip_srch" tabindex="-1"></a>
        <div id="searchWrapper" role="search">
            <form id="searchForm" name="f_szukaj" method="get" action="index.php">
                <input name="c" type="hidden" value="search" />
                <input name="action" type="hidden" value="search" />
                <fieldset>  
                <legend>Szukaj</legend>
                    <label for="kword"  class="hide"><? echo $TXT['srch_label']; ?>:</label>
                    <input type="text" id="kword" class="isearch" name="kword" size="24" maxlength="40" value="<? echo $TXT['srch_txt']; ?>" onfocus="if (this.value=='<? echo $TXT['srch_txt']; ?>') {this.value=''};" onblur="if (this.value=='') {this.value='<? echo $TXT['srch_txt']; ?>'};"/>
                    <p><a href="index.php?c=search"><span class="hide">Wyszukiwanie </span>Zaawansowane</a></p>
                    <input type="submit" name="search" value="<? echo $TXT['srch_button']; ?>" class="button"/>
                    <div class="clear"></div>
                </fieldset>  
            </form>		
        </div>	    
                                
	</div>
    
</div>