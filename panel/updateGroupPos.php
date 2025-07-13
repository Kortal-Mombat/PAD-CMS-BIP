<?php
if ($showPanel){
	parse_str($_REQUEST['groupPos'], $groupPos);
	
	$menuType = $_REQUEST['menuType'];
	
	$resMod = new resClass;

	if (count($groupPos) > 0){
		$n = 1;
		foreach($groupPos['grId'] as $key => $value){
			$id = $key;
			$parent_id = $value;
			$pos = $n;
			
			$n++;
			
			if ($parent_id == 'null'){				
				$parent_id = 0;
			}
			$sql = "UPDATE `".$dbTables['pages']."` SET `pos`=?, `ref`=? WHERE `id`=? AND `menutype`=? ";
			
			$params = array(
					'pos'		=>	$pos,
					'ref'		=>	$parent_id,
					'id'		=>	$id,
					'menutype'	=>	$menuType
					);
			$res -> bind_execute($params, $sql);

		}
	}
	
		function get_panel_menu_tree ($ref = 0, $numline = 0)
		{
			global $dbTables, $lang, $MSG_del_confirm,  $shortDate;;
	
			// max ilosc zagniezdzen
			if ( $_SESSION['mt'] == 'tm') {
				$nestNum = 2;
			}
			if ( $_SESSION['mt'] == 'mg') {
				$nestNum = 3;
			}
				
			$res = new resClass;
			
			//debug($res);
	
			$sql = "SELECT * FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`ref` = ?) AND (`lang` = ?) ORDER BY pos";
			$params = array (
					'menutype' => $_SESSION['mt'],
					'ref' => $ref, 
					'lang' => $lang
					);
			$res->bind_execute( $params, $sql);
			$numPages = $res->numRows;
			
			if ($numPages > 0)			
			{
				echo '<ul class="connectedSortable">';
				$n = 0;
				foreach ($res->data as $row)
				{
					$n++;
					if ( get_priv_pages($row['id']) ) 
					{
					    $rowColor = '';
					    
						$res2 = new resClass;
						$sql = "SELECT COUNT(id) AS numRef FROM `" . $dbTables['pages'] . "` WHERE (`ref` = ?) AND (`lang` = ?)";
						$params = array (
								'ref' => $row['id'], 
								'lang' => $lang
								);
						$res2->bind_execute( $params, $sql);				
						$numRef = $res2->data[0]['numRef'];
						
						$numline++;	
						$active = '';
						if ($row['active'] == 1) {
							$active_url = '<a href="'.$PHP_SELF.'?c=page&amp;action=noactive&amp;id=' . $row['id'] . '" title="Ukryj"><img src="template/images/icoStat1.png" alt="Ukryj" class="imgAct" /></a> ';
							
						}
						else {
							$active_url = '<a href="'.$PHP_SELF.'?c=page&amp;action=active&amp;id=' . $row['id'] . '" title="Pokaz"><img src="template/images/icoStat0.png" alt="Pokaz" class="imgAct" /></a> ';
							$active = ' class="noActive"';
						}
						$liClass = 'menTLi';
						$num = $res->numRows;
						if ($num == $n){
							$liClass = ' menTLiLast';
						}
						
						if ( $numline == $nestNum){
							$liClass .= ' no-nest';
						}
						
						$row['start_date'] = substr($row['start_date'], 0, 10);
						$row['stop_date'] = substr($row['stop_date'], 0, 10);
						
						if ($row['start_date']!='0000-00-00' && $row['stop_date']!='0000-00-00')
							$odDo = $row['start_date'].'<br/>'.$row['stop_date'];
						else
							$odDo = 'bez przerwy';
							
						if (!($row['start_date']<=$shortDate && $row['stop_date']>=$shortDate) && ($row['start_date']!='0000-00-00' && $row['stop_date']!='0000-00-00') || $row['active']==0) 
						{
							$rowColor = ' noactive';
						}
												
						echo '<li id="grId_' . $row['id'] . '" class="'. $liClass . $rowColor .'">';

						if ($numRef > 0)
							$spacja = '';
						else
							$spacja = '';
		
						$w = ($numline -1) * 50;
						$spacja .= '' ;
							
						
						echo '<div class="menuTreeLi move">'
								. $spacja . '<span class="menuTreeIco"></span><span class="menuTreeName">' .$i . $row['name'] . '</span>'
								.'<div class="menuTreeCells">'
									.'<div class="menuTreeShow">'.$active_url.'</div>'
									.'<div class="menuTreePos"><label for="id_'.$row['id'].'" class="hide">Ustal kolejność dla ' . $row['name'] . '</label>'
										.'<select name="pos_'.$row['id'].'" id="id_'.$row['id'].'" class="selPos">';
										for ($j=1; $j<=$numPages; $j++)
										{
											if ($row['pos'] == $j)
												echo '<option selected="selected">'.$j.'</option>';
											else
												echo '<option>'.$j.'</option>';
										} 
										echo '</select>';

									echo '</div>'

									.'<div class="menuTreeAction">'
										.'<a href="'.$PHP_SELF.'?c=page&amp;action=edit&amp;id=' . $row['id'] . '" title="Edytuj pozycje"><img src="template/images/icoEdit.png" alt="Edytuj pozycje" class="imgAct" /></a> ';
								
									echo '<a href="'.$PHP_SELF.'?c=articles&amp;idp=' . $row['id'] . '&amp;mt='.$_SESSION['mt'].'" title="Artykuły"><img src="template/images/icoArticles.png" alt="Artykuły" class="imgAct" /></a> ';
									
										if ($row['type'] == 'dynamic') 
										{
											echo '<a href="javascript:confirmLink(\'' . $PHP_SELF . '?c=page&amp;action=delete&amp;id=' . $row['id'] . '\',\'' . $MSG_del_confirm . '\');" title="Usuń pozycję" class="delLink"><img src="template/images/icoDel.png" alt="Usun pozycje" class="imgAct" /></a> ';
										}									
								echo '</div>'
								.'</div>'
							.'</div>';							
						get_panel_menu_tree ($row['id'], $numline);
						$numline--;	
						echo '</li>';
					}
					
				}
				echo '</ul>';
			}
		}		
	get_panel_menu_tree();
echo '<script type="text/javascript">' . "\r\n";
echo '// <![CDATA[' . "\r\n";
	echo '$(document).ready(function() {' . "\r\n";
		echo '$(function() {'. "\r\n";
			echo "$('.connectedSortable').nestedSortable({" . "\r\n";
				echo "disableNesting: 'no-nest'," . "\r\n";
				echo "forcePlaceholderSize: true," . "\r\n";
				echo "handle: 'div', " . "\r\n";
				echo "helper:	'clone'," . "\r\n";
				echo "items: 'li'," . "\r\n";
				echo "maxLevels: 3," . "\r\n";
				echo "opacity: 0.7," . "\r\n";
				echo "placeholder: 'emptyList'," . "\r\n";
				echo "revert: 250," . "\r\n";
				echo "tabSize: 30," . "\r\n";
				echo "tolerance: 'pointer'," . "\r\n";
				echo "toleranceElement: '> div'," . "\r\n";
				echo "delay: 100," . "\r\n";
				echo "listType: 'ul'," . "\r\n";
				echo "update: function(){" . "\r\n";
				echo 'var send = $("#menuTree ul").nestedSortable("serialize");' . "\r\n";;
					echo '$.post("index.php?c=updateGroupPos", {' . "\r\n";;
						echo 'groupPos:send,' . "\r\n";
						echo "menuType:'".$_SESSION['mt']."'" . "\r\n";
					echo "}, function(response){" . "\r\n";
						echo '$("#menuTree").html(response);' . "\r\n";
					echo '});' . "\r\n";
				echo '}' . "\r\n";
			echo '});' . "\r\n";
		echo '});' . "\r\n";
	echo '});' . "\r\n";
echo '// ]]>' . "\r\n";
echo '</script>' . "\r\n";
}
?>