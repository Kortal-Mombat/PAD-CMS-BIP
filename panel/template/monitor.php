<?php
if (!$showMonitor)
{
	echo '<h2>Monitor aktywności</h2>';
	echo $message;
		
	?>
	<table width="100%">
			<caption>Ilość monitorów: <?= $numRows; ?></caption>
			<tr><th width="5%">L.p</th><th width="65%">Użytkownik</th><th width="20%">Ostatnia wizyta</th><th width="10%">Akcja</th></tr>
			<?php
				$pole = $i = 0;
				foreach ($outRow as $rec)
				{
					$i++;
					
					$pole++;
					if ($pole==1) { 
						$rowColor = ''; 
					}
					if ($pole==2) { 
						$rowColor = ' class="rowInv"';
						$pole = 0; 
					}  
										
					if ($rec['id_user'] == 0)
					{
						$name = 'Nieautoryzowany';
					}
					else if ($rec['name'] == NULL)
					{
						$name = 'Użytkownik nieprzypisany (Id: ' . $rec['id_user'] . ')';
					}
					else
					{
						$name = $rec['name'] . ' (' . $rec['login'] . ')';
					}
						
					echo '<tr'.$rowColor.'>'
						.'<td>' . $i . '.</td>'
						.'<td><a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=show&amp;id=' . $rec['id_user'] . '">' . $name . '</a></td>'
						.'<td>' . $rec['last_visit'] . '</td>'
						.'<td class="butIcons">'
							.'<a href="javascript: confirmLink(\'' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=del&amp;id=' . $rec['id_user'] . '\',\'' . $MSG_del_confirm . '\');" title="Usuń pozycję"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a>'
						.'</td>'
						.'</tr>';
				}
			?>
	</table>
		
			<div class="legend">
			    <span class="bolder">Legenda:</span>
			    <?php
			    
			    include_once(CMS_TEMPL . DS . 'legend_icons.php');
			    ?>
			</div>	
		
	<?php
}
else if ($showMonitor)
{
	echo '<h2>Monitor aktywności dla: '. $userName .'</h2>';
	echo $message;
	
	?>
	<ul class="navMenu">
    	<li><a href="<?= $PHP_SELF.'?c=' . $_GET['c'];?>"><span class="icoBack"></span>Wstecz</a></li>
    </ul>
	<?php
	if ($numRows > 0)
	{
		?>
		<table class="records" width="100%">
			<caption>Ilość pozycji: <?= $numRows; ?></caption>
            <tr><th width="5%">Lp.</th><th width="20%">Data</th><th width="50%">Wykonane zadanie</th><th width="25%">Numer IP</th></tr>
			<?php
				$pole = $i = 0;
				foreach ($outRow as $rec )
				{
					$i++;
					$pole++;
					if ($pole==1) { 
						$rowColor = ''; 
					}
					if ($pole==2) { 
						$rowColor = ' class="rowInv"';
						$pole = 0; 
					}      
					echo '<tr'.$rowColor.'>'
							.'<td>' . ($i+$sql_start) . '.</td>' 	
							.'<td>' . $rec['date'] . '</td>' 		
							.'<td>' . $rec['action'] . '</td>' 
							.'<td>' . $rec['ip'] . '</td>'
						.'</tr>';
				}
			?>
		</table>            
		<?php
		$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=show&amp;id=' . $_GET['id'] . '&amp;s=';
		include (CMS_TEMPL . DS . 'pagination.php');	

	}
}
?>
