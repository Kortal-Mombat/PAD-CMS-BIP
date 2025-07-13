<?php
	echo $message;
?>

<h2><?= $pageTitle . ' <span class="cmsVer">(wersja: ' . $cms_version . ')</span>'; ?></h2>
    
<div id="start">
    <div id="startUser" class="startDiv">
	<?php
	echo $TXT_user . '<br />';
	echo '<span class="userName">' . $_SESSION['userData']['name'] . '</span><br /><br />';
	$last = explode(' ', $_SESSION['userData']['last_visit']);
	if (is_numeric($last[0])) {
		$day = date('l', $last[0]);
	} else {
		$day = date('l');
	}
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

    <div class="startInfo">
		<h2>Historia zmian:</h2>
		<ul>
			<li>1.2.1.ak3 - 2025-07-13</li>
				<ul>
					<li>Poprawki bezpieczeństwa</li>
					<li>Ujednolicenie stylu mobilnego</li>
				</ul>
			</li>
			<li>1.2.1.ak2 - 2024-04-24</li>
				<ul>
					<li>Poprawki bezpieczeństwa</li>
				</ul>
			</li>
			<li>1.2.1.ak1 - 2023-08-11</li>
				<ul>
					<li>Dostosowanie do PHP 8.2</li>
					<li>Ujednolicenie przekierowań z http na https</li>
					<li>Poprawki bezpieczeństwa</li>
				</ul>
			</li>
			<li>1.2.1 - 2019-09-19</li>
				<ul>
					<li>Pominięcie sprawdzania rozszerzenia mysql w PHP</li>
					<li>Usunięcie błędów spowodowanych wersją PHP 7.x</li>
				</ul>
			</li>
			<li>1.2.0 - 2016-04-25</li>
				<ul>
					<li>Dodanie informacji pomocniczych do formularza instalacyjnego oraz dodanie wymaganych pól (e-mail)</li>
					<li>Dodanie przypominania hasła użytkownika przy formularzu logowania do panelu administracyjnego</li>
					<li>Dodanie licznika limitu czasu bezczynności użytkownika w panelu administracyjnym z możliwością przedłużenia</li>
					<li>Dodanie numeru wersji CMS w panelu administracyjnym</li>
					<li>Poprawa wyświetlania kolejności artykułów na stronie głównej</li>
					<li>Poprawa licznika wejść</li>
					<li>Poprawa literówek</li>
					<li>Poprawa ukrywania podstron</li>
					<li>Poprawa ankiety</li>
					<li>Dodatkowe określenie wymaganych pól w panelu administracyjnym (WAI ARIA)</li>
					<li>Aktualizacja edytora TinyMCE 4 - wersja 4.3.10</li>
					<li>Dodanie w edytorze TinyMCE 4:</li>
					<ul>
						<li>Sprawdzanie pisowni</li>
						<li>Wyświetlanie bloków HTML</li>
						<li>Znajdź i zamień</li>
						<li>Zakładka "Zaawansowane" w okienku wstawiania/edycji zdjęcia.</li>
						<li>Przycisk zmiany języka dowolnego fragmentu tekstu oraz wyróżnienie go kolorem</li>
					</ul>
				</ul>
			</li>
		</ul>
    </div>
</div>

