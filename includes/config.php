<?php
/**
 * Podstawowa konfiguracja.
 * Ten plik zawiera konfiguracje ustawień MySQL, które
 * możesz zdobyć od administratora Twojego serwera
 * oraz numer szablonu, z którego korzysta CMS.
 */

/** 
 * Nazwa szablonu, który jest wykorzystany. 
 * Zmień TYLKO wtedy, jeśli szablon znajduje się w innym katalogu.
 */
$templateDir = 'template_bip';

/** Konfiguracja bazy danych MySQL */
$dbConfig = array(

	/** Sterownik bazy danych. Nie zmieniaj proszę. */
	'driver' => 'mysql',
	
	/** Nazwa hosta serwera bazy danych */
	'host' => '',

	/** Nazwa użytkownika bazy danych */
	'user' => '',

	/** Hasło użytkownika bazy danych */
	'pass' => '',

	/** Nazwa bazy danych */
	'dbname' => '',

	/** Port połączenia z bazą danych.
	 * Niektóre bazy danych działają na innych portach niż domyślny.
	 */	
	'port' => 3306,

	/**
	 * Prefiks tabel w bazie danych.
	 * Możesz uruchomić kilka stron PAD CMS w jednej bazie danych.
	 * Stosuj TYLKO cyfry, litery i znaki podkreślenia.
	 */
	'prefix' => 'pad_bip_',

	/** Zestaw znaków dla połączeń z bazą danych. Nie zmieniaj proszę. */
	'setnames' => 'utf8'
);
	
/**
 * Przy każdorazowej instalacji zmień klucz, aby był unikalny.
 * Posłuży on do szyfrowania hasła użytkowników panelu administracyjnego. 
 */
$salt = 'kD62epj3V^:RaF1Oo7D*b4TO+vZ/mK5f.$X*7yM4d';

/** Pozostałe ustawienia */
include_once ( CMS_ROOT . DS . 'includes' . DS . 'settings.php' );
?>