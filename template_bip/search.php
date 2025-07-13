<h2><?php echo $pageName; ?></h2>

<script type="text/javascript">

$(document).ready(function(){  
	/* Kalendarzyk */
	
	$(".datepicker").datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: templateDir + "/images/calendar.gif",
			buttonText: "Wybierz datę"
		});
});		

</script>
<?php
	echo '<div class="txtWrapper">';
	echo $message;
	?>
        <div id="searchWrapperAdv">
            <form id="searchFormAdv" name="f_szukaj_adv" method="get" action="index.php">
                <input name="c" type="hidden" value="search" />
                <input name="action" type="hidden" value="searchAdv" />
                <fieldset>  
                <legend>Wyszukiwanie zaawansowane</legend>
                    
                    <label for="kwordAdv"><?php echo $TXT['srch_label']; ?>:</label>
                    <input type="text" id="kwordAdv" name="kword" size="30" value="<?php echo trim($_GET['kword']); ?>" onfocus="if (this.value=='<?php echo $TXT['srch_txt']; ?>') {this.value=''};" onblur="if (this.value=='') {this.value='<?php echo $TXT['srch_txt']; ?>'};"/>
                    <br/>
                    
                    <label for="data_od">Data poczatkowa (rok-miesiac-dzien):</label> 
                    <input name="od" class="datepicker" type="text" maxlength="10" size="11" value="<?php echo $input_od; ?>" id="data_od"/> 
                    <br/>
                    
                    <label for="data_do">Data koncowa (rok-miesiac-dzien):</label> 
                    <input name="do" class="datepicker" type="text" maxlength="10" size="11" value="<?php echo $input_do; ?>" id="data_do"/>
                    <br/>
                    
					<label for="os_wpr">Osoba wprowadzajaca:</label>
					<input type="text" name="os_wpr" id="os_wpr" size="30" value="<?php echo $_GET['os_wpr']; ?>" />
                    <br/>

					<label for="os_odp">Osoba sporzadzajaca:</label>
					<input type="text" name="os_odp" id="os_odp" size="30" value="<?php echo $_GET['od_odp']; ?>" />
                    <br/>
                    
                    <input type="submit" name="searchAdv" value="<?php echo $TXT['srch_button']; ?>" />
                    
                    <div class="clear"></div>
                </fieldset>  
            </form>		
        </div>
        	
	<?php
	echo '<div class="searchList">';
	if ($searchCount > 0)
	{
		for ($i=$searchStart; $i<($searchStart+$pageConfig['limit']); $i++)
		{
			echo '<div class="searchTxt">'
				.'<h3>'.$searchArray[$i]['url'].'</h3>'
				.'<div class="searchLeadTxt"><p>'.$searchArray[$i]['lead'].'</p></div>'
				.'</div>';
		}
	}
	
	echo '</div>';
	if ( $_GET['action'] == 'searchAdv')
	{
		$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action='.$_GET['action'].'&amp;kword=' . $_GET['kword'] . '&amp;od=' . $_GET['od'] . '&amp;do=' . $_GET['do'] . '&amp;os_wpr=' . $_GET['os_wpr'] . '&amp;os_odp=' . $_GET['os_odp'] . '&amp;s=';
	}
	else
	{
		$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action='.$_GET['action'].'&amp;kword=' . $_GET['kword'] . '&amp;s=';
	}
	include (CMS_TEMPL . DS . 'pagination.php');
	
	echo '</div>';		
?>