<?php
/*
 * Pomoc do dymków
 */

$help = array(
    /*
     * Ustawienia
     */
    'settings'	    => array(
		'pagename'	    => 'Wpisz nazwę strony – wyświetli się na stronie głównej.',
		'address'	    => 'Wpisz adres – wyświetli się na stronie głównej w danych teleadresowych.',
		'logo'	    	=> 'Wstaw logo serwisu – wyświetli się na stronie głównej.',
		'email'	   		=> 'Adres e-mail będzie docelowym adresem dla formularza kontaktowego.',
		'animType'	    => 'Wybierz typ animacji zdjęć w banerze górnym',
		'duration'	    => 'Ustal czas pokazywania zdjęć w banerze górnym',
		'transition'	=> 'Ustal czas odstępu między pojawiającymi się kolejno zdjęciami',
		'artNumStart'	=> 'Wybierz ilość artykułów wyświetlanych na stronie głównej. (*przy dużej ilości informacji należy ustawić odpowiednią ilość wyświetlanych artykułów, aby uniknąć przewijania strony w dół i zapewnić dogodne stronicowanie).',
		'funeral'	    => 'Włącz/wyłącz wersję żałobną',
		'metaTitle'	    => 'Wpisz tytuł strony wyświetlany w pasku przeglądarki, np. Urząd Miasta w Częstochowie.',
		'metaKey'	    => 'Wpisz słowa-klucze, po których Twoja strona ma być wyszukiwana w wyszukiwarce, np. urząd miasta Częstochowa, przyjazny urząd',
		'metaDesc'	    => 'Opisz krótko zawartość strony, np. Serwis samorządowy, aktualności i wydarzenia z regionu.',
		'host'	   		=> 'Pełny adres internetowy strony. Niezbędny do prawidłowego działania serwisu',
        'www'           => 'Pełny adres internetowy strony www podmiotu.'
    ),
    /*
     * Menu główne i dodatkowe
     */
    'menu'	    => array(
		'group'		    => 'Wybierz, czy dodawana zakładka ma być zakładką główną lub czy ma się znajdować wewnątrz wybranej zakładki już istniejącej.',
		'title'		    => 'Wpisz nazwę zakładki.',
		'url_name'	    => 'Nazwa, która pojawi się w tym miejscu, pojawi się w pasku adresu przeglądarki www.',
		'redirect'	    => 'Otwiera przekierowanie w nowym oknie.',
		'redirectInfo'  => 'Jeśli w polu znajduje się adres URL (przekierowanie), to pozostałe informacje nie będą widoczne na stronie.',
		'date_add'	    => 'Informuje o dacie dodania pozycji na stronę.',
		'articles'	    => 'Ustal ilość artykułów wyświetlanych w zakładce.',
		'author'	    => 'Imię i nazwisko autora artykułu wyświetli się pod tekstem.',
		'gallery'	    => 'Jeśli strona lub artykuł posiada zdjęcia, będą one wyświetlone w module galeria.',
		'protected'	    => 'Określ, czy zakładka będzie dostępna tylko po zalogowaniu.',
		'metaTitle'	    => 'Wpisz tytuł zakładki wyświetlany w pasku przeglądarki, np. Skontaktuj się z nami.',
		'metaKey'	    => 'Wpisz słowa-klucze, po których zakładka ma być wyszukiwana w wyszukiwarce (np. Google), np. historia, przyjazny urząd w Częstochowie.',
		'metaDesc'	    => 'Opisz krótko zawartość zakładki, np. zakładka zawierająca informacje o historii miasta.'	
    ),
    /*
     * Artykuły
     */
    'articles'	    => array(
		'title'		    => 'Wpisz nazwę artykułu.',
		'url_name'	    => 'Nazwa, która pojawi się w tym miejscu, pojawi się w pasku adresu przeglądarki www.',
		'position'	    => 'Ustal kolejność wyświetlania artykułów na stronie.',
		'redirect'	    => 'Otwiera przekierowanie w nowym oknie.',
		'redirectInfo'  => 'Jeśli w polu znajduje się adres URL (przekierowanie), to pozostałe informacje nie będą widoczne na stronie.',
		'date_add'	    => 'Informuje o dacie dodania artykułu na stronę.',
		'date_from'	    => 'Wpisz datę od kiedy artykuł będzie wyświetlany na stronie.',
		'date_to'	    => 'Wpisz datę do kiedy artykuł będzie wyświetlany na stronie.',
		'author'	    => 'Imię i nazwisko autora artykułu wyświetli się pod tekstem.',
		'home'		    => 'Artykuł będzie wyświetlony na stronie głównej serwisu.',
		'highlight'	    => 'Wyróżni artykuł na stronie, np. kolorową ramką.',
		'gallery'	    => 'Jeśli strona lub artykuł posiada zdjęcia, będą one wyświetlone w module galeria.',
		'protected'	    => 'Określ, czy artykuł będzie dostępny tylko po zalogowaniu.',
		'metaTitle'	    => 'Wpisz tytuł artykułu wyświetlany w pasku przeglądarki, np. Skontaktuj się z nami.',
		'metaKey'	    => 'Wpisz słowa-klucze, po których artykuł ma być wyszukiwany w wyszukiwarce (np. Google), np. historia, dobra podstawówka w Częstochowie.',
		'metaDesc'	    => 'Opisz krótko zawartość artykułu, np. zakładka zawierająca informacje o historii miasta.'		
    ),
    /*
     * Tekst powitalny
     */
    'welcome'	    => array(
		'show'		    => 'Zaznacz, jeśli tekst powitalny ma być wyświetlony na stronie głównej.'
    )
);

?>
