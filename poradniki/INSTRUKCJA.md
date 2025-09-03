# Instrukcja instalacji i konfiguracji

## 1. Konfiguracja bazy danych
- Otwórz plik:
  ```
  /includes/config.php
  ```
- Wprowadź dane dostępowe do bazy danych:
  ```php
  $db_host = 'localhost';
  $db_name = 'nazwa_bazy';
  $db_user = 'uzytkownik_bazy';
  $db_pass = 'haslo_bazy';
  ```
- Ustaw unikalny klucz bezpieczeństwa:
  ```php
  $salt = 'własny_unikalny_ciag_znakow';
  ```

## 2. Instalacja
- Uruchom w przeglądarce:
  ```
  http://www.twoja-domena.pl/install
  ```
- Postępuj zgodnie z instrukcjami instalatora.

## 3. Czyszczenie po instalacji
- Usuń katalog:
  ```
  /install
  ```

## 4. Ustawienia PHP w `.htaccess`
Dodaj w pliku `.htaccess`:
```apache
php_value error_log /ścieżka/do/logs/PHP_errors.log
```
> Zamień `/ścieżka/do/logs/` na faktyczną ścieżkę katalogu logów na serwerze.

## 5. Ograniczenie dostępu po adresach IP (panel administracyjny)
W pliku `.htaccess` w katalogu panelu administracyjnego ustaw reguły dostępu:

```apache
Order Deny,Allow
Deny from all

# Dozwolone podsieci (adresy dokumentacyjne)
Allow from 192.0.2.0/29
Allow from 198.51.100.128/29

# Dozwolone pojedyncze adresy IP (adresy dokumentacyjne)
Allow from 198.51.100.10
Allow from 198.51.100.20
Allow from 203.0.113.5
```

## 6. Uwagi końcowe
- Adresy IP w przykładach są **adresami dokumentacyjnymi** – w środowisku produkcyjnym należy je zamienić na prawdziwe.
- Po każdej zmianie w `.htaccess` warto sprawdzić logi serwera, aby upewnić się, że reguły działają poprawnie.
- W środowisku produkcyjnym **nie włączaj** `display_errors` – błędy powinny być logowane, a nie wyświetlane użytkownikowi.
