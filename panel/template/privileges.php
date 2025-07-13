<h2><?php echo $pageTitle; ?></h2>

<ul class="navMenu">
    <li><a href="<?php echo $PHP_SELF.'?c=users'; ?>"><span class="icoShow"></span>Pokaż wszystkich</a></li>
    <li><a href="<?php echo $PHP_SELF.'?c=users&amp;action=add'; ?>"><span class="icoAdd"></span>Dodaj użytkownika</a></li>	
</ul>

<div class="clear"></div>

<?php
echo $message;

if ($numRowsMenu>0)
{
    echo '<form name="formPriv" class="formEdAdd" method="post" action="'.$PHP_SELF.'?c='.$_GET['c'].'&amp;id='.$_GET['id'].'&amp;action=update">';
    echo '<div class="formWrap">';
    echo '<ul class="menuPriv">';
    foreach ($outRowMenu as $menuRow)
    {
		if ($menuRow['ref']==0 && $menuRow['controler']!='users')
		{
			$checked = '';
			for ($j=1; $j<=count($mp_idrec);$j++)
			{ 	 
				if (isset($mp_idrec[$j]) && ($menuRow['id_mp'] == $mp_idrec[$j]))
					$checked = ' checked="checked"';
			}					
			echo '<li>'
				.'<input type="checkbox" name="mp_'.$menuRow['id_mp'].'" id="mp_'.$menuRow['id_mp'].'" value="'.$menuRow['id_mp'].'" '.$checked.'/> '
				.'<label for="mp_'.$menuRow['id_mp'].'" class="checkInput">'.$menuRow['name'].'</label>';
		
			if ($menuRow['controler'] == 'page' || $menuRow['controler'] == 'dynamic_menu')
			{
			
				$tmp = explode ('&', $menuRow['link']);
				$mt = explode ('=', $tmp[0]);
				$menuType = $mt[1];
		
				$sql = "SELECT COUNT(id) As numPage FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`lang` = ?)";
				$params = array('menutype' => $menuType, 'lang' => $lang);
				$res->bind_execute( $params, $sql );	
		
				$mt_count = $res->data[0]['numPage'];
		
				show_priv_checkbox($mt_count, $menuType, 0, $page_idrec);
				
				/*
				 * Dynamic menu
				 */
				if ($menuRow['controler'] == 'dynamic_menu')
				{
					echo '<ul class="menuPriv indent">';
					$sql = "SELECT * FROM `" . $dbTables['menu_types'] . "` WHERE (`menutype` <> 'mg') AND (`menutype` <> 'tm') AND `lang` = ? ORDER BY `pos`";
					$params = array('lang' => $lang);
					$res->bind_execute( $params, $sql );
					$outMenu = $res->data;
					$dynMenu = array();
					foreach ($outMenu as $value)
					{
						$checked = '';
						for ($j=1; $j<=count($mpd_idrec);$j++)
						{ 	 
							if ($value['id_menu'] == $mpd_idrec[$j])
								$checked = ' checked="checked"';
						}				
						
						$sql = "SELECT COUNT(id) As numPage FROM `" . $dbTables['pages'] . "` WHERE (`menutype` = ?) AND (`lang` = ?)";
						$params = array('menutype' => $value['menutype'], 'lang' => $lang);
						$res->bind_execute( $params, $sql );
						$mt_count = $res->data[0]['numPage'];
						
						echo '<li>'
							.'<input type="checkbox" name="mpd_'.$value['id_menu'].'" id="mpd_'.$value['id_menu'].'" value="'.$value['id_menu'].'" '.$checked.'/> '
							.'<label for="mpd_'.$value['id_menu'].'" class="checkInput">'.$value['name'].'</label>';			
						show_priv_checkbox($mt_count, $value['menutype'], 0, $page_idrec);
						
						echo '</li>';
					}
					echo '</ul>';
				}
			}
			//get_submenu($outRowMenu, $menuRow['id_mp'], $_GET['c']);
	
			echo '</li>';	
		}
    }	
    echo '</ul>';	
    echo '<div><img src="template/images/icoArrowSel.png" alt="" /> <a href="#" onclick="on_box(); return false;" class="button2">Zaznacz wszystkie</a> / <a href="#" onclick="off_box(); return false;" class="button2">Odznacz wszystkie</a></div>';
    echo '</div>';
    echo '<input type="submit" value="Zapisz" class="butSave" name="save"/>';		
    echo '</form>';		
}
?>