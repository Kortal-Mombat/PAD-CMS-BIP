<?php
	echo $message;
?>

<h2><? echo $pageTitle . ' <span class="cmsVer">(wersja: ' . $cms_version . ')</span>'; ?></h2>
    
<div id="start">
    <div id="startUser" class="startDiv">
	<?php
	echo $TXT_user . '<br />';
	echo '<span class="userName">' . $_SESSION['userData']['name'] . '</span><br /><br />';
	$last = explode(' ', $_SESSION['userData']['last_visit']);
	$day = date('l', $last[0]);
	$date = $last[0];
	echo $TXT_last_visit . ':<br /><span class="bolder sm">' . showHumanDate($_SESSION['userData']['last_visit']) . ', godz.' . substr($last[1], 0, -3).'</span>';
	?>
    </div>

    <div id="startCounter" class="startDiv">
	<?php
	echo '<div class="sCount">Ilość odwiedzin: <span class="bolder">' . $counter . '</span></div>';
	echo '<div class="sPage">Najczęściej czytana strona: <span><a href="../index.php?c=page&amp;id='. $countPage['id'] .'" target="_blank" class="sm">' . $countPage['name'] . ' (' . $countPage['counter'] . ')</a></span></div>';
	echo '<div class="sArticle">Najczęściej czytany artykuł: <span><a href="../index.php?c=article&amp;id='. $countArticle['id_art'] .'" target="_blank" class="sm">' . $countArticle['name'] . ' (' . $countArticle['counter'] . ')</a></span></div>';
	?>
    </div>
    <br class="clear" />
    
    <div class="startInfo">
    <h2>Informacje:</h2>
    <ul>
	    <li>Instrukcja obsługi panelu administracyjnego dostępna jest w <a href="http://pad.widzialni.org/container/samouczki/samouczek--czyli-jak-korzystac-z-panelu-administracyjnego-dostepnego-biuletynu-informacji-publicznej-pad.doc" title="Dokument DOC - 7MB">samouczku</a>.</li>
   		<li>W panelu administracyjnym dostępne są dwa edytory tekstowe TinyMCE 3 oraz TinyMCE 4. Dla użytkowników korzystających z programów czytających lub wykorzystujących systemowy tryb wysokiego kontrastu zaleca się wykorzystanie edytora TinyMCE w najnowszej wersji. Wersję edytora można zmienić w menu Ustawienia, w zakładce Panel.
        <li>Po wejściu w pole edycyjne edytora, do jego narzędzi bez użycia myszki możesz wejść wykorzystując skróty:</li>
        	<ul>
				<li>TinyMCE 3: ALT + F10 (ALT + 0 - pomoc)</li>
            	<li>TinyMCE 4: ALT + F9 - menu, ALT + F10 - narzędzia</li>                
            </ul>
        </li>
    </ul>
    </div>
</div>

