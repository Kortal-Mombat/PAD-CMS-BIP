<?php
	ini_set('url_rewriter.tags', '');
	ini_set('session.use_trans_sid', false);
	
	session_start();
	
	header('Content-Type: text/html; charset=utf-8');
	
	/**
	 * Zdefiniowanie stalych
	 */
	define('DS', DIRECTORY_SEPARATOR);
	define( 'CMS_BASE', dirname(__FILE__) );
	$parts = explode( DS, CMS_BASE );
	array_pop( $parts );
	define( 'CMS_ROOT', implode( DS, $parts ) );
	define( 'CMS_TEMPL', CMS_BASE .  DS . 'template');
		
	/**
	 * Dolaczenie plików konfiguracyjnych 
	 */
	include_once ( CMS_ROOT . DS . 'includes' . DS . 'config.php' );
	include_once ( CMS_ROOT . DS . 'includes' . DS . 'functions.php' );
	include_once ( CMS_ROOT . DS . 'includes' . DS . 'set.php' );		
	include_once ( CMS_ROOT . DS . 'panel/includes' . DS . $lang . DS . 'messages.php');
	
	$message .= show_msg ('info', '
			<p>Witaj!</p>
			<p>Uruchomiłeś instalację systemu PAD CMS.</p>' 
	);	

	$loadProblems = 0;	
	$showForm = false;	
	$php_err = false;
	$ini_err = false;
	$write_err = false;	
	
	$php_ext = array ('iconv', 'gd', 'PDO');
	$count = 0;
	foreach ($php_ext as $k => $v)
	{
		if (!extension_loaded($v))
		{
			$count++;
			$php_err['ext'] .= $v . ', ';
		}
	}

	if (version_compare(PHP_VERSION, $php_version, '<'))
	{
		$ini_err .= '<li>Potrzebujesz PHP w wersji <strong>'.$php_version.'</strong> lub wyższej aby uruchomić tą wersję PAD CMS.</li>';
	}
	
	if ((int)ini_get('memory_limit') < 64) {
		$ini_err .= '<li>Zmienna <code>memory_limit</code> nie może być mniejsza niż <strong>64MB</strong></li>';
	}
	
	if (ini_get('safe_mode') == 1 ) {
		$ini_err .= '<li>Zmienna <code>safe_mode</code> musi posiadać wartość <strong>off</strong></li>';
	}
	
	if (ini_get('short_open_tag') == 0 ) {
		$ini_err .= '<li>Zmienna <code>short_open_tag</code> musi posiadać wartość <strong>on</strong></li>';
	}
		
	if (ini_get('register_globals') == 1 ) {
		$ini_err .= '<li>Zmienna <code>register_globals</code> musi posiadać wartość <strong>off</strong></li>';
	}
				
	if ($php_err != false)
	{
		$message .= show_msg ('err', '
				<p>Instalacja nie może być kontynuowana, ponieważ brakuje niezbędnych rozszerzeń PHP takich jak: '.substr($php_err['ext'], 0, -1).'</p>
			');	
	}
	else if ($ini_err != false)
	{
		$message .= show_msg ('err', '
				<p>Instalacja nie może być kontynuowana z następujących powodów:</p>
				<ul>'.$ini_err.'</ul>
				<p>Zmień ustawienia w pliku <code>php.ini</code> lub poproś o to administratora Twojego serwera.</p>
			');		
	}
	else
	{
		if (!$_GET['step'])
		{
			$message .= show_msg ('info', '
				<p>Zanim rozpoczniesz pracę na stronie internetowej, musisz ustawić dostęp do bazy danych. Potrzebne będą następujące dane:</p>
				<ul>
					<li>Nazwa bazy danych</li>
					<li>Nazwa użytkownika bazy danych</li>
					<li>Hasło użytkownika bazy danych</li>
					<li>Adres serwera bazy danych</li>
					<li>Port dla połączeń bazy danych (opcjonalnie, jeśli jest inny niż domyślny 3306)</li>
				</ul>
				<p>Powyższe dane uzyskasz od administratora Twojego serwera.</p>
				<p>Wpisz dane w pliku <code>config.php</code> znajdującym się w katalogu <code>includes</code>.
			');
			
			if (! is_writable('../container')) 
			{
				if (! @chmod('../container', 0777)) {
					$write_err = true;
				}
				if (! @chmod('../download', 0777)) {
					$write_err = true;
				}				
				if (! @chmod('../files', 0777)) {
					$write_err = true;
				}
				if (! @chmod('../files/pl', 0777)) {
					$write_err = true;
				}
				if (! @chmod('../files/pl/mini', 0777)) {
					$write_err = true;
				}
				
				if ($write_err)
				{
					$message .= show_msg ('err', '
						<p>Uwaga! Do jednego z katalogów nie udało się ustawić prawa do zapisu.</p>
						<p>Do prawidłowego działania PAD CMS musisz ustawić prawa do zapisu dla następujących katalogów:</p>
						<ul>
							<li><code>container</code></li>
							<li><code>download</code></li>
							<li><code>files</code></li>
							<li><code>files/pl</code></li>
							<li><code>files/pl/mini</code></li>
						</ul>	
						<p>Jeżeli nie jesteś w stanie tego wykonać skontaktuj się z administratorem serwera.</p>				
					');				
				}									
			}
			
			if (!$write_err)
			{
				$message .= show_msg ('info', '
					<p><a href="'.$PHP_SELF.'?step=1" class="button">Rozpocznij instalację</a></p>
				');			
			}
		}
		
		if ($_GET['step'] == 1)
		{
			if (check_config())
			{
				try{
					$pdo = new PDO($dbConfig['driver'] . ':host=' . $dbConfig['host'] . ';port=' . $dbConfig['port'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $dbConfig['setnames']));
					$showForm = true;
				}
				catch(PDOException $e){
					$message .= show_msg ('err', '
						<h2>Brak połączenia z bazą danych!</h2>
						<p>Sprawdź czy nazwa użytkownika, hasło, nazwa oraz adres bazy danych są poprawnie wpisane w pliku konfiguracyjnym.</p>
						<p>Jeżeli są poprawne skontaktuj się z administratorem serwera. </p>	
						<p><a href="'.$PHP_SELF.'?step=1" class="button">Spróbuj jeszcze raz</a></p>						
					');
				}				
			}
			else
			{
				$message .= show_msg ('err', '
					<h2>Uwaga!</h2>
					<p>Plik konfiguracyjny nie został uzupełniony.</p>
					<p><a href="'.$PHP_SELF.'?step=1" class="button">Spróbuj jeszcze raz</a></p>						
				');
			}
		}
		
		if ($_GET['step'] == 2)
		{
			include_once ( CMS_ROOT . DS . 'includes' . DS . 'db.php' );				

			if (!$_POST['login'])
			{
				$message .= show_msg ('err', $ERR_login_inst);
				$showForm = true;
			}
			else if (trim($_POST['passwd']) == '')
			{
				$message .= show_msg ('err', $ERR_passwd_inst);
				$showForm = true;
			}
			else  if (strlen(trim($_POST['passwd'])) < $passLength)
			{
				$message .= show_msg ('err', $ERR_passwd_min . $passLength);
				$showForm = true;
			}				
			else if ($_POST['passwd'] != $_POST['passwd2'])
			{
				$message .= show_msg ('err', $ERR_passwd_eq);
				$showForm = true;
			}
			else if (!check_email_addr($_POST['email']))
			{
				$message .= show_msg ('err', $ERR_email);
			} 				
			else if (trim($_POST['host']) == '')
			{
				$message .= show_msg ('err', $ERR_www_inst);
				$showForm = true;
			}	
			else
			{
				include_once ( CMS_BASE . DS . 'schema.php' );							
				
				if ($loadProblems == 0)	
				{
					$res = new resClass;						
					
					$in = array ('http://');
					$out = array ('');							
					
					$sql = "UPDATE `" . $dbTables['settings'] . "` SET `attrib`= 
						CASE
							WHEN `id_name` = 'pagename' THEN ?
							WHEN `id_name` = 'host' THEN ?
							WHEN `id_name` = 'email' THEN ?
							ELSE `attrib`
						END";
					$params = array(
						'pagename'	=> $_POST['pagename'],
						'host'	=> str_replace($in, $out, $_POST['host']),
						'email'	=> $_POST['email'],				
					);
					$res->bind_execute($params, $sql);
						
					$sql = "UPDATE `" . $dbTables['users'] . "` SET name = ?, login = ?, passwd = ?, email = ? WHERE (`id_user` = ?) LIMIT 1";

					$f_pass = sha1($_POST['passwd'].$salt);		
					$params = array (
								'name' => $_POST['name'], 
								'login' => $_POST['login'], 
								'passwd' => $f_pass, 
								'email' => $_POST['email'], 
								'id' => 1 
								);							
					
					$res->bind_execute( $params, $sql);
					$numRows = $res->numRows;		
					
					if ( $numRows > 0 )	
					{		
						$message .= show_msg ('msg', '
							<h2>Gratulacje!</h2>
							<p>Instalacja przebiegła pomyślnie.</p>
							<p>Usuń katalog <code>install</code> z serwera, aby korzystać ze strony PAD CMS.</p>	
							<p><a href="../index.php" class="button">Rozpocznij</a></p>						
						');
						unset($_POST);
					}
					else
					{
						$message .= show_msg ('err', '
							<h2>Niepowodzenie!</h2>
							<p>Instalacja nie została dokończona.</p>
							<p>Nie udało się ustawić danych użytkownika PAD CMS.</p>	
							<p>Skontaktuj się z administratorem swojego serwera.</p>						
						');
					}	
				}
				else
				{
					if (stristr($problemList, 'exists') || stristr($problemList, 'duplicate') )
					{
						$message .= show_msg ('err', '
							<h2>Uwaga!</h2>
							<p>Instalacja prawdopodobnie została już przeprowadzona.</p>
							<p>Jeśli tak, usuń katalog <code>install</code> z serwera, aby korzystać ze strony PAD CMS.</p>	
							<p><a href="../index.php" class="button">Rozpocznij</a></p>						
							<p>Poniżej lista napotkanych problemów:</p>
							<p>'.str_replace(',' ,'<br/>', $problemList).'</p>
						');						
					}
					else
					{
						$message .= show_msg ('err', '
							<h2>Niepowodzenie!</h2>
							<p>Instalacja nie została dokończona, ponieważ wystąpiły błędy w ilości: <strong>'.$loadProblems.'</strong>.</p>
							<p>Nie udało się załadować wszystkich tabel lub danych do bazy danych.</p>	
							<p>Poniżej lista błędów:</p>
							<p>'.str_replace(',' ,'<br/>', $problemList).'</p>
							<p>Skontaktuj się z administratorem swojego serwera.</p>						
						');
					}
				}
			}					
		} // end step = 2		
	}

	include_once ( CMS_TEMPL . DS . 'index.php');

?>