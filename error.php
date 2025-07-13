<?
	$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';

	$url_home = 'Powrót do strony głównej serwisu';
	
	switch ($_GET['e']){
	
		case 400 :
			$title = 'Niepoprawne zapytanie (Bad Request)';
			$err = 'Błąd 400';
			$txt = 'Twoja przeglądarka przesłała zapytanie, które nie może zinterpretować serwer. Błąd w składni zapytania.';		
		break;		

		case 401 :
			$title = 'Brak autoryzacji (Authorization Required)';
			$err = 'Błąd 401';
			$txt = 'Zapytanie nie przeszło pomyślnie procesu uwierzytelnienia. Serwer nie może zweryfikować czy posiadasz autoryzację dostępu do dokumentu, który żądasz. Prawdopodobnie wprowadziłes nieprawidłowe dane jak np. hasło lub twoja przeglądarka nie potrafi dostarczyć właściwych danych do autoryzacji.';		
		break;		

		case 403 :
			$title = 'Dostęp zabroniony (Forbidden)';
			$err = 'Błąd 403';
			$txt = 'Zapytanie odrzucone przez serwer. Nie masz dostępu do tego zasobu na tym serwerze.';		
		break;		
						
		case 404 : 
			$title = 'Plik nie istnieje (File Not Found)';
			$err = 'Błąd 404';
			$txt = 'Podany w zapytaniu URL nie został odnaleziony na tym serwerze.';
		break;

		case 405 :	
			$title = 'Niedozwolona metoda (Method Not Allowed)';
			$err = 'Błąd 405';
			$txt = 'Metoda nie jest obsługiwana przez wybrany URL .';		
		break;	
		
		case 406 :	
			$title = 'Brak akceptacji (Not Acceptable)';
			$err = 'Błąd 406';
			$txt = 'Format podanego URL nie jest akceptowany przez serwer.';		
		break;	
		
		case 408 :	
			$title = 'Przekroczony czas oczekiwania (Request Time-out)';
			$err = 'Błąd 408';
			$txt = 'Przekroczony czas na przygotowanie zapytania. ';		
		break;	
		
		case 410 :	
			$title = 'URI usunięte (Gone)';
			$err = 'Błąd 410';
			$txt = 'Podany w zapytaniu URL jest już nie dostępny na tym serwerze. Proszę usunąć wszystkie odwołania do tego URL.';		
		break;	
		
		case 500 :	
			$title = 'Wewnętrzny błąd serwera (Internal Server Error)';
			$err = 'Błąd 500';
			$txt = 'Wewnętrzny błąd serwera (np. zawieszenie programu CGI) lub błąd konfiguracji. Proszę skontaktować się z <a href="mailto:'.$host_mail.'" title="Kontakt do administratora">administratorem</a> i poinformować go o czasie pojawienia się błędu wraz z podaniem innych informacji, które mogły być przyczyną błędu.';		
		break;	
		
		case 503 :	
			$title = 'Usługi tymczasowo niedostępne (Service Temporarily Unavailable)';
			$err = 'Błąd 503';
			$txt = 'Usługi serwera są czasowo niedostępne co może być spowodowane jego zatrzymaniem lub przeładowaniem. Proszę spróbować połączyć się później.';
		break;					
	}
	
	$pageTitle = $title . ' - ' . $pageTitle;
	$crumbpath[] = array ('name' => $err, 'url' => '');
?>