<div id="toolbarWrapper">
    <div id="toolbar">
		
        <div id="linksWrapper">
	    <ul>
                <li><a href="mobile" class="topLink icoHome"><? echo $TXT['but_start']; ?></a></li>
            	<li><a href="pelna_wersja" class="topLink icoNormal">Pełna wersja</a></li>                
                <li><a href="mapa_strony" class="topLink icoSitemap"><? echo $TXT['site_map']; ?></a></li>
                <li><a href="http://bip.gov.pl" title="<? echo $TXT['but_bip']; ?>" class="bipLink"><img src="<?php echo $templateDir; ?>/images/logoBIPTop.png" alt="<? echo $TXT['but_bip_short']; ?>" /></a></li>
                <?php 
				if ($pageInfo['www'] != '')
				{
					?>
					<li><a href="<?php echo $pageInfo['www']; ?>" class="topLink icoHome">Serwis podmiotu</a></li>
					<?php
				}
				?>
		<br class="clear" />
            </ul>
        </div>
        
     	<a name="skip_srch" id="skip_srch"></a>
        <div id="searchWrapper">
	    <h2>Wyszukaj</h2>
            <form id="searchForm" name="f_szukaj" method="get" action="index.php">
                <input name="c" type="hidden" value="search" />
                <fieldset class="borderNone">  
                <legend class="hide"><? echo $TXT['srch_legend']; ?></legend>
                    <label for="kword"  class="hide"><? echo $TXT['srch_label']; ?>:</label>
                    <input type="text" id="kword" class="isearch" name="kword" size="24" maxlength="40" value="<? echo $TXT['srch_txt']; ?>" onfocus="if (this.value=='<? echo $TXT['srch_txt']; ?>') {this.value=''};" onblur="if (this.value=='') {this.value='<? echo $TXT['srch_txt']; ?>'};"/>
                    <input type="submit" name="search" value="<? echo $TXT['srch_button']; ?>" title="<? echo $TXT['srch_button']; ?>" class="btnSearch"/>
                    <div class="clear"></div>
                </fieldset>  
            </form>		
        </div>	    
          
        <div class="clear"></div>

    </div>
</div>

<div id="templateSep"></div>