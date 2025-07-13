<?php 
	$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."articles` (
  `id_art` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `url_name` varchar(255) NOT NULL default '',
  `lead_text` text NOT NULL,
  `text` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `wprowadzil` varchar(255) NOT NULL,
  `podmiot` varchar(255) NOT NULL,
  `attrib` text NOT NULL,
  `ext_url` varchar(255) NOT NULL default '',
  `new_window` tinyint(3) unsigned NOT NULL default '0',
  `active` tinyint(3) unsigned NOT NULL default '0',
  `show_on_main` tinyint(4) NOT NULL default '0',
  `highlight` tinyint(4) NOT NULL default '0',
  `protected` tinyint(4) NOT NULL default '0',
  `ingallery` tinyint(4) NOT NULL,
  `type` enum('static','dynamic') NOT NULL default 'static',
  `lang` varchar(4) NOT NULL default '',
  `create_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `show_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `stop_date` datetime NOT NULL,
  `counter` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id_art`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."art_to_pages` (
  `id_page` int(10) unsigned NOT NULL default '0',
  `id_art` int(10) unsigned NOT NULL default '0',
  `pos` int(10) unsigned NOT NULL default '0',
  KEY `id_page` (`id_page`),
  KEY `id_art` (`id_art`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."counter` (
  `id` int(11) NOT NULL auto_increment,
  `count` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."files` (
  `id_file` int(11) NOT NULL auto_increment,
  `id_page` int(11) NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `pos` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL,
  `keywords` text NOT NULL,
  `data` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id_file`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."menu_panel` (
  `id_mp` smallint(5) unsigned NOT NULL auto_increment,
  `ref` smallint(5) unsigned NOT NULL,
  `pos` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL default '',
  `controler` varchar(32) NOT NULL,
  `link` varchar(255) NOT NULL default '',
  `active` smallint(5) unsigned NOT NULL,
  `lang` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`id_mp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."menu_types` (
  `id_menu` smallint(6) unsigned NOT NULL auto_increment,
  `menutype` varchar(8) NOT NULL default '',
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `attrib` text NOT NULL,
  `type` enum('static','dynamic') NOT NULL default 'static',
  `active` tinyint(4) unsigned NOT NULL,
  `lang` varchar(6) NOT NULL,
  `pos` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id_menu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."monitor` (
  `id_mon` int(10) unsigned NOT NULL auto_increment,
  `id_user` smallint(5) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `action` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_mon`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `menutype` varchar(8) NOT NULL default '',
  `ref` int(10) unsigned NOT NULL default '0',
  `pos` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `url_name` varchar(255) NOT NULL default '',
  `lead_text` text NOT NULL,
  `text` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `wprowadzil` varchar(255) NOT NULL,
  `podmiot` varchar(255) NOT NULL,
  `attrib` text NOT NULL,
  `ext_url` varchar(255) NOT NULL default '',
  `new_window` tinyint(3) unsigned NOT NULL default '0',
  `active` tinyint(3) unsigned NOT NULL default '0',
  `protected` tinyint(4) NOT NULL default '0',
  `ingallery` tinyint(4) NOT NULL,
  `type` enum('static','dynamic') NOT NULL default 'static',
  `lang` varchar(4) NOT NULL default '',
  `create_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `show_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `stop_date` datetime NOT NULL,
  `counter` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ref` (`ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."photos` (
  `id_photo` int(11) NOT NULL auto_increment,
  `id_page` int(11) NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `pos` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL,
  `keywords` text NOT NULL,
  `data` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id_photo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."privileges` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_user` int(10) unsigned NOT NULL default '0',
  `id_tbl` varchar(16) NOT NULL default '',
  `id_rec` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."register` (
  `id` int(11) NOT NULL auto_increment,
  `idp` int(11) NOT NULL,
  `idg` int(11) NOT NULL default '0',
  `podmiot` varchar(255) NOT NULL default '',
  `os_sporz` varchar(128) NOT NULL default '',
  `os_wprow` varchar(128) NOT NULL default '',
  `data_utw` datetime NOT NULL default '0000-00-00 00:00:00',
  `data_publ` datetime NOT NULL default '0000-00-00 00:00:00',
  `old_text` text NOT NULL,
  `akcja` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."settings` (
  `id_set` int(10) unsigned NOT NULL auto_increment,
  `id_name` varchar(16) NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `attrib` text NOT NULL,
  `type` varchar(16) NOT NULL,
  `values` varchar(255) NOT NULL,
  `tab` varchar(100) NOT NULL,
  `lang` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`id_set`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."users` (
  `id_user` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `login` varchar(32) NOT NULL,
  `passwd` varchar(64) NOT NULL,
  `type` varchar(16) NOT NULL,
  `email` varchar(128) NOT NULL,
  `auth_key` varchar(64) NOT NULL,  
  `last_visit` datetime NOT NULL,
  `active` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY  (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$sql_tables[] = "CREATE TABLE `".$dbConfig['prefix']."viewer` (
  `id_viewer` varchar(50) NOT NULL,
  `text` mediumtext NOT NULL,
  PRIMARY KEY  (`id_viewer`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (1, 0, 1, 'Start', '-', '', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (2, 0, 5, 'Menu podmiotowe', 'page', 'mt=tm', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (3, 0, 10, 'Menu przedmiotowe', 'page', 'mt=mg', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (4, 0, 15, 'Dla administratorów', '', '', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (5, 0, 20, 'Użytkownicy panelu administracyjnego', 'users', '', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (6, 0, 25, 'Monitor aktywności', 'monitor', '', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (17, 0, 12, 'Menu w stopce', 'page', 'mt=ft', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (12, 0, 2, 'Ustawienia ogólne', 'settings', '', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (14, 0, 18, 'Pliki na serwerze', 'explorer', '', 1, 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_panel` VALUES (18, 0, 14, 'Wyszukiwarka treści', 'search', '', 1, 'pl');";

$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."counter` VALUES (1, 1);";

$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_types` VALUES (1, 'tm', 'Menu podmiotowe', '', '', 'dynamic', 1, 'pl', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_types` VALUES (2, 'mg', 'Menu przedmiotowe', '', '', 'dynamic', 1, 'pl', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."menu_types` VALUES (3, 'ft', 'Stopka', '', '', 'static', 1, 'pl', 0);";

$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (1, 'ft', 0, 2, 'Redakcja BIP', 'redakcja-bip', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (2, 'ft', 0, 4, 'Oświadczenie o dostępności', 'oswiadczenie-o-dostepnosci', '', '<p>Serwis został zaprojektowany tak, aby był możliwy do obsłużenia dla jak najszerszej grupy użytkowników, niezależnie od używanej technologii, oprogramowania lub posiadanej dysfunkcji.<br />Nieustannie poszukujemy rozwiązań aby zwiększyć dostępność i użyteczność naszej strony internetowej. Jeżeli masz jakieś uwagi albo komentarze skontaktuj się z nami.</p>\r\n<h3>Zgodność ze standardami:</h3>\r\n<p>Serwis jest zgodny ze standardami <abbr title=\"World Wide Web Consortium\" lang=\"en\">W3C</abbr>:</p>\r\n<ul>\r\n<li><abbr title=\"Extensible HyperText Markup Language\" lang=\"en\">HTML 5</abbr></li>\r\n<li><abbr title=\"Web Content Accessibility Guidelines\" lang=\"en\">WCAG</abbr> 2.0 (Podwójne A)</li>\r\n</ul>\r\n<h3>Kompatybilność:</h3>\r\n<p>Serwis jest w pełni rozpoznawalny przez programy czytające dla osób niewidomych Window-Eyes, JAWS czy NVDA.<br />Obsługa serwisu możliwa jest zarówno przy pomocy klawiatury jak i myszki.</p>\r\n<h3>Wygląd:</h3>\r\n<p>Serwis jest wyposażony w mechanizmy ułatwiające przeglądanie treści przez osoby niedowidzące. Zmiana wielkości czcionki, zmiana kontrastu.<br />Całość serwisu oparta jest na stylach CSS.</p>\r\n<h3>Skróty klawiaturowe:</h3>\r\n<p>Serwis nie jest wyposażony w skróty klawiaturowe, które mogły by wchodzić w konflikt z technologiami asystującymi (np. programy czytające), systemem lub aplikacjami użytkowników.</p>', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'static', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (3, 'ft', 0, 3, 'Instrukcja korzystania z BIP', 'instrukcja-korzystania-z-bip', '', '<p>Biuletyn Informacji Publicznej to urzędowy publikator teleinformatyczny, składający się z ujednoliconego systemu stron w sieci informatycznej, na których zostaje udostępniona informacja publiczna.</p>\r\n<h3>Uwarunkowania prawne</h3>\r\n<p>Uruchomienie Biuletynu Informacji Publicznej jest niezbędne dla funkcjonowania ustawy z 6 września 2001 r. o dostępie do informacji publicznej (Dz. U. Nr 112, poz. 1198, z późn. zm.) oraz Rozporządzenia MSWiA z 18 stycznia 2007 r. w sprawie BIULETYNU INFORMACJI PUBLICZNEJ.</p>\r\n<p>Strona Biuletynu Informacji Publicznej podmiotu składa się z kilku podstawowych elementów:</p>\r\n<ol>\r\n<li>\r\n<p>Menu górnego (w nagłówku strony) zawierającego odsyłacz do strony głównej BIP pod adresem <a href=\"http://www.bip.gov.pl/\">www.bip.gov.pl</a>, ustawienia ułatwiające dla osób niepełnosprawnych, wyszukiwarkę, statystykę odwiedzin oraz dane podmiotu prowadzącego BIP.</p>\r\n</li>\r\n<li>\r\n<p>Menu nawigacji (poniżej Menu górnego) wskazującego użytkownikowi aktualną pozycję na stronie, tzw. ścieżki okruszków znajdującej się po frazie jesteś tutaj:. Po kliknięciu w link strona główna w tym menu powoduje powrót do strony głównej BIP podmiotu.</p>\r\n</li>\r\n<li>\r\n<p>Menu przedmiotowego i podmiotowego (po lewej stronie witryny) zawierającego odsyłacze do najważniejszych części strony, w tym do Spisu Podmiotów.</p>\r\n</li>\r\n<li>\r\n<p>Właściwej treści strony zamieszczonej w środkowej części dokumentu. W tym miejscu będą pokazywać się dane podmiotów, artykuły, treści dokumentów i ogłoszeń itp.</p>\r\n</li>\r\n<li>\r\n<p>Menu dolnego (w stopce strony) zawierającego Instrukcję korzystania z BIP, Oświadczenie o dostępności strony BIP, informację o Redakcji BIP, Kontakt oraz Mapę Strony.</p>\r\n</li>\r\n</ol>\r\n<p>Podstawowym przeznaczeniem strony BIP jest prezentacja informacji na temat podmiotów zobowiązanych do prowadzenia stron BIP.</p>\r\n<p>Zamieszczone na stronie dane podmiotów zobowiązanych do prowadzenia stron BIP oraz artykuły opatrzone są metadanymi informacji publicznej, czyli</p>\r\n<ol>\r\n<li>\r\n<p>tytułem zasobu,</p>\r\n</li>\r\n<li>\r\n<p>czasem wytworzenia zasobu,</p>\r\n</li>\r\n<li>\r\n<p>tożsamością osoby wytwarzającej zasób,</p>\r\n</li>\r\n<li>\r\n<p>tożsamością osoby odpowiadającej treść zasobu,</p>\r\n</li>\r\n<li>\r\n<p>rejestrem wprowadzonych zmian,</p>\r\n</li>\r\n<li>\r\n<p>statystyką wyświetleń zasobu.</p>\r\n</li>\r\n</ol>\r\n<p>Aby zapoznać się z informacjami w Biuletynie Informacji Publicznej należy kliknąć w odpowiedni link w menu przedmiotowym lub podmiotowym. Do precyzyjnego wyszukiwania informacji służy Wyszukiwarka znajdująca się w górnej części serwisu. Aby skorzystać z wyszukiwarki należy wpisać w pole edycyjne szukaną frazę i nacisnąć przycisk szukaj. Aby skorzystać z opcji rozszerzonych wyszukiwarki należy kliknąć wyszukiwanie zaawansowane.</p>\r\n<p>Aby zmienić kontrast serwisu należy kliknąć ikonkę zmiana kontrastu. Aby zmienić wielkość czcionki należy kliknąć ikonki czcionka duża lub czcionka średnia. Aby powrócić do ustawień fabrycznych należy wybrać ikonkę ustawienia domyślne.</p>', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (4, 'ft', 0, 1, 'Kontakt', 'kontakt', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'static', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (8, 'ft', 0, 5, 'Polityka prywatności', 'polityka-prywatnosci', '', '<p>Serwis internetowy nie udostępnia osobom trzecim informacji pochodzących z formularzy kontaktowych umieszczonych w serwisie, poczty e-mail przesyłanej bezpośrednio chyba że stosowny organ administracji publicznej lub sąd wystąpi o ich udostępnienie. [USTAWA z dnia 29 sierpnia 1997 r. o ochronie danych osobowych.(Dz. U. z dnia 29 października 1997 r.)]</p>\r\n<p>Wszelkie dane osobowe lub instytucji pochodzące z formularzy kontaktowych w tym serwisie lub poczty e-mail przesyłanej bezpośrednio nie są przedmiotem obrotu handlowego.</p>\r\n<p>Niektóre elementy serwisu mogą wykorzystywać cookies (małe pliki wysyłane do komputera użytkownika identyfikując go do wykonania określonej operacji). Przykład: zmiana wielkości lub kontrastu czcionki. Warunkiem działania cookies jest ich akceptacja przez przeglądarkę.</p>\r\n<p>Nie odpowiadamy za politykę prywatności serwisów, do których prowadzą linki z tego serwisu</p>\r\n<p>Zbieramy i analizujemy dane wynikające z logów systemowych.</p>\r\n<p>Jako propagatorzy idei dostępności i użyteczności stron internetowych w Internecie dokładamy wszelkich starań aby nasz serwis był maksymalnie bezpieczny dla użytkowników.</p>', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'static', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (11, 'mg', 0, 1, 'Projekty ekologiczne', 'projekty-ekologiczne', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (12, 'mg', 0, 2, 'Nasze sprawy', 'nasze-sprawy', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (13, 'mg', 0, 3, 'Na sportowo', 'na-sportowo', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (14, 'tm', 0, 1, 'Struktura', 'struktura', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (15, 'tm', 0, 2, 'Władze', 'wladze', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."pages` VALUES (16, 'tm', 0, 3, 'Historia', 'historia', '', '', 'Administrator', 'Administrator', '', '', '', 0, 1, 0, 0, 'dynamic', 'pl', '".$date."', '".$date."', '".$date."', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);";

$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (1, 'pagename', 'Nazwa strony', '', 'text', '', 'baner', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (2, 'logo', 'Logo serwisu', '<p><img src=\"".$_POST['host']."/container/logoPAD.png\" alt=\"Logo PAD\" width=\"76\" height=\"78\" /></p>', 'html', '', 'baner', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (3, 'email', 'Adres E-mail', '', 'text', '', 'baner', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (4, 'host', 'Adres strony www', '', 'text', '', 'main', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (6, 'metaTitle', 'Meta title', '', 'text', '', 'seo', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (10, 'metaKey', 'Meta keywords', '', 'text', '', 'seo', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (15, 'metaDesc', 'Meta description', '', 'text', '', 'seo', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (20, 'artNumStart', 'Ilość artykułów na głównej', '3', 'text', '5', 'view', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (25, 'emailFormContact', 'Kontaktowy adres e-mail', '', 'text', '', 'main', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (30, 'pluginFB', 'Wtyczka Facebook', 'włącz', 'radio', 'włącz,wyłącz', 'social', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (40, 'pluginTweet', 'Wtyczka Twitter', 'włącz', 'radio', 'włącz,wyłącz', 'social', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (8, 'activeWww', 'Zablokuj stronę z przyczyn technicznych:', 'nie', 'radio', 'nie,tak', 'main', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (45, 'editor', 'Edytor tekstowy', 'TinyMCE 4', 'radio', 'TinyMCE 3,TinyMCE 4', 'panel', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (7, 'activeTextWww', 'Treść strony zablokowanej', '<p>Przepraszamy Trwa przerwa techniczna. Prace zostaną zakończone w godzinach nocnych.</p>', 'html', '', 'main', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (5, 'www', 'Adres strony www podmiotu', '', 'text', '', 'main', 'pl');";
$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."settings` VALUES (9, 'address', 'Adres', '<p><strong>Fundacja Widzialni</strong><br />42-200 Częstochowa<br />Tel. 34 325 40 41</p>', 'html', '', 'view', 'pl');";

$sql_insert[] = "INSERT INTO `".$dbConfig['prefix']."users` VALUES (1, 'Administrator', 'admin', '0cfd1abba0b8ff5cf637bdb5a30ad4071931cb8a', 'admin', '".$_POST['email']."', '', '0000-00-00 00:00:00', 1);";

$problemList = '';
$res = new resClass;
	
foreach ($sql_tables as $k => $v)
{
	$params = array ();							
	$res->bind_execute( $params, $v);
	if ($res->error != '')	
	{
		$loadProblems++;	
		$problemList .= $res->error . ',';
		$res->error = '';
	}
}

foreach ($sql_insert as $k => $v)
{
	$params = array ();							
	$res->bind_execute( $params, $v);
	if ($res->error != '')	
	{
		$loadProblems++;
		$problemList .= $res->error . ',';	
		$res->error = '';
	}		
}

unset($res)

?>