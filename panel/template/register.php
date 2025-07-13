<?
if (!$showMonitor)
{
	echo '<h2>Rejestr zmian dla: '. $articleName .'</h2>';
	echo $message;
	
	?>
	<ul class="navMenu">
    	<li><a href="<? echo $backLink; ?>"><span class="icoBack"></span>Wstecz</a></li>
    </ul>
	<?
	if ($_GET['action'] == 'show')
	{
		echo '<h3>Poprzednia wersja artykułu</h3>';
		echo $articleText;
	}
	
	if ($showList)
	{	
		if ($numRows > 0)
		{
			?>
			<table class="records" width="100%">
				<caption>Ilość pozycji: <? echo $numRows; ?></caption>
				<tr><th width="5%">Lp.</th><th>Os. sporz.</th><th>Os. wprow.</th><th>Data publ.</th><th>Opis zmiany</th><th>Akcja</th></tr>
				<?
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
								.'<td>' . $rec['os_sporz'] . '</td>' 		
								.'<td>' . $rec['os_wprow'] . '</td>' 
								//.'<td>' . $rec['data_utw'] . '</td>'
								.'<td>' . $rec['data_publ'] . '</td>'
								.'<td>' . $rec['akcja'] . '</td>'
								.'<td class="butIcons">'
									.'<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;mt=' . $_SESSION['mt'] . '&amp;idp=' . $_GET['idp'] . '&amp;action=show&amp;id='.$_GET['id'].'&amp;idr='.$rec['id'].'" title="Poprzednia wersja"><img src="template/images/icoGoto.png" alt="Poprzednia wersja" class="imgAct" /></a>'
									.'<a href="javascript: confirmLink(\'' . $delLink . '&amp;idr=' . $rec['id'] . '\',\'' . $MSG_del_confirm . '\');" title="Usuń pozycję"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a>'
								.'</td>'
								
							.'</tr>';
					}
				?>
			</table>            
			<?
			/*
			$url = $PHP_SELF.'?c=' . $_GET['c'] . '&amp;id=' . $_GET['id'] . '&amp;idp=' . $_GET['idp'] . '&amp;s=';
			include (CMS_TEMPL . DS . 'pagination.php');				
			*/
		}
	}
}
?>
