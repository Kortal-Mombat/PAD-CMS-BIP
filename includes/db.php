<?
	class pdoClass extends PDO {
	
		private static $myInstance = false;	
		
		public function __construct() 
		{
		
		}
		
		// Instancja klasy
		public static function getInstance() 
		{
			if( self :: $myInstance == false ) {
				
				include( CMS_ROOT . DS . 'includes' . DS . 'config.php' );
				
				if (!empty($dbConfig['port'])) {
					$dbConfig['port'] = ';port=' . $dbConfig['port'];
				}
				
				try 
				{
				    self :: $myInstance = new PDO($dbConfig['driver'] . ':host=' . $dbConfig['host'] . $dbConfig['port'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $dbConfig['setnames']));
				} 
				catch (PDOException $e) 
				{
				    echo '<p>Wystąpił błąd w połączeniu z bazą danych. Sprawdź ustawienia w pliku konfiguracyjnym lub skontaktuj się z administratorem.</p>';
					exit();
				}
				
			} 
			return self :: $myInstance;
		} 
	}
	
	class resClass {
		
		// Obiekt PDO
		protected $pdo;
				
		// Obiekt PDOStatment 
		protected $stmt;
		
		// dane wyjsciowe
		public $data = array();
		
		// zwraca ilosc wierszy
		public $numRows = 0;

		// zwraca ostatni dodany id
		public $lastID = 0;
		
		// bledy z bazy
		public $error = '';
			
		// Ilosc zapytan
		static private $_queries = 0;
		
		// Ilosc obiektow
		static private $_objectCount = 0;		
		
		/**
		 * Konstruktor 
		 */					
		public function __construct () 
		{
			// nazwiaznie polaczenia z baza
			$this->pdo = pdoClass :: getInstance();
			self::$_objectCount++;			
		} 
								
		/**		
		 * Przygotowanie zapytania
		 * Sprawdzenie typu i dolacznie parametrow do sql
		 * Sprawdzane typy INT i STR
		 * Wykonanie zapytania
		 */
		public function bind_execute ( $params, $sql )
		{
			$this->stmt = $this->pdo->prepare($sql);

			//dolaczenie
			$i = 1;
			foreach ($params as $k => $v)
			{
				if (is_int($v)) 
				{
					$this->stmt->bindValue($i, $v, PDO::PARAM_INT); 
				}
				else if (is_string($v)) 
				{
					$v = stripslashes($v);
					$this->stmt->bindValue($i, $v, PDO::PARAM_STR); 
				}
				else 
				{
					$v = stripslashes($v);
					$this->stmt->bindValue($i, $v, PDO::PARAM_STR); 
				}
				$i++;				
			}
			
			// wykonanie
			$this->stmt->execute();	
			
			if (!strstr($sql, 'INSERT') &&  !strstr($sql, 'DELETE'))
			{
				$this->data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			
			if (strstr($sql, 'INSERT'))
			{
				$this->lastID = $this->pdo->lastInsertId();
			}
			
			$this->numRows = $this->stmt->rowCount();
			$this->get_error();	
						
			// zliczanie zapytan
			self::$_queries++;
		}
		
		/**
		 * Sprawdzenie i ustawienie ewnetualnych bledow z bazy
		 */ 
		public function get_error()
		{
			$error_arr = $this->stmt->errorInfo();
			if ($error_arr[1]!='')
			{
				$this->error = $error_arr[0] . ': '. $error_arr[1] . ' - ' . $error_arr[2] . '. ';
			}
		}		
		
		/**
		 * Zliczanie zapytan 
		 */
		static public function getQueries () 
		{
			return self::$_queries;
		} 
		
		/**
		 * Zliczanie powstalych obiektow 
		 */
		static public function getObjectCount () 
		{
			return self::$_objectCount;
		} 		 		
	}
?>
