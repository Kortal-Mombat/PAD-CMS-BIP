<?php
	ini_set('url_rewriter.tags', '');
	ini_set('session.use_trans_sid', false); 
	
	// error_reporting(E_ALL);
	
	session_start();
	
	/**
	 * Zdefiniowanie stalych
	 */ 	
	define( 'DS', DIRECTORY_SEPARATOR );
	define( 'CMS_BASE', dirname(__FILE__) );

	$parts = explode( DS, CMS_BASE );
	array_pop( $parts );
	define( 'CMS_ROOT', implode( DS, $parts ) );
	define( 'CMS_TEMPL', CMS_BASE .  DS . 'template');
	
	include_once ( CMS_ROOT . DS . 'includes' . DS . 'check.php' );
	/**
	 * Dolaczenie plików konfiguracyjnych 
	 */ 
	include_once ( CMS_BASE . DS . 'includes' . DS . 'load.php');

	/**
	 * Sprawdzenie czy zalogowany 
	 */ 
	$showPanel = false;
	if (check_login_user())
	{
		// po zalogowaniu beda dolaczone pozostale pliki do szablonu: top, left, bottom, etc
		$showPanel = true;	
		include_once ( CMS_BASE . DS . 'left.php');	
	}
	else
	{
		setCSS('login.css', $css);
	}
		 	
	include_once ( CMS_BASE . DS . 'login.php');
	
	$sql = "SELECT COUNT(id_art) AS total_records FROM `" . $dbTables['art_to_pages'] . "` WHERE (`id_page`= ?)";
	$params = array( 'id_page' => $_GET['idp']);
	$res->bind_execute( $params, $sql);
	
	$r = $res->data[0];	
	$numRows = $r['total_records'];		
		
	if ($numRows > 0)
	{						
		$sql = "SELECT `" . $dbTables['art_to_pages'] . "`.* , `" . $dbTables['articles'] . "`.* 
				FROM `" . $dbTables['art_to_pages'] . "` LEFT JOIN `" . $dbTables['articles'] . "` 
				ON `" . $dbTables['art_to_pages'] . "`.id_art=`" . $dbTables['articles'] . "`.id_art
				WHERE (`" . $dbTables['art_to_pages'] . "`.id_page = ?)
				ORDER BY `" . $dbTables['art_to_pages'] . "`.pos  LIMIT ".$sql_start.", ".$sql_limit;	
				
		$params = array( 'id_page'=> $_GET['idp']);
		$res->bind_execute( $params, $sql);
		$outRow = $res->data;	

		$pagination = pagination ($numRows, $cmsConfig['limit'], 2, $_GET['s']);		
	}	
	
?>
<div id="articleList">
	    <table width="100%" id="rowList">
        <caption>Ilość pozycji: <?= $numRows; ?></caption>
        <tr><th width="5%">L.p</th><th width="70%">Tytuł</th><th width="15%">Pokazać</th><th width="10%">Akcja</th></tr>
        <tbody>
        <?php
            
            $pole = $i = 0;
            foreach ($outRow as $row)
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
                    
                $active_url = '';

                if ($row['active'] == 1) {
                    $active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=noactive&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '" title="Ukryj"><img src="template/images/icoStat1.png" alt="Ukryj" class="imgAct" /></a> ';
                }
                else {
                    $active_url = '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=active&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '" title="Pokaż"><img src="template/images/icoStat0.png" alt="Pokaż" class="imgAct" /></a> ';
                }
                                
                echo '<tr'.$rowColor.' id="artId_' . $row['id_art'] . '"><td>' . ($i+$sql_start) . '.</td>'
                    .'<td>' . $row['name'] . '</td>'
                    .'<td>' . $active_url . '</td>'
                    .'<td>';

                echo '<a href="'.$PHP_SELF.'?c=' . $_GET['c'] . '&amp;action=edit&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '" title="Edytuj pozycję"><img src="template/images/icoEdit.png" alt="Edytuj pozycję" class="imgAct" /></a> ';
                echo '<a href="javascript: confirmLink(\'' . $PHP_SELF . '?c=' . $_GET['c'] . '&amp;action=delete&amp;id=' . $row['id_art'] . '&amp;idp=' . $_GET['idp'] . '\',\'' . $MSG_del_confirm . '\');" title="Usuń pozycję"><img src="template/images/icoDel.png" alt="Usuń pozycję" class="imgAct" /></a> ';

                echo '</td>'
                    .'</tr>';
            }
        ?>
        </tbody>
    </table>
</div>