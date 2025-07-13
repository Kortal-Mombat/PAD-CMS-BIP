<?php
	$res = new resClass;
	// $date = date('Y-m-d H:i:s');
	if (isset($_SESSION['userData']) && is_array($_SESSION['userData']) && isset($_SESSION['userData']['UID'])) {
		$sql = "UPDATE `" . $dbTables['users'] . "` SET 
				last_visit = ? 
				WHERE id_user = '" .$_SESSION['userData']['UID'] . "' 
				LIMIT 1";
				
		$params = array('last_visit' => $date);
		$res->bind_execute( $params, $sql );
		$numRows = $res->numRows;	
				
		monitor( $_SESSION['userData']['UID'], $MON_logout, get_ip() );
	}
	clean_session();
	
	header('Location: '.url_redirect ('index.php'));
	exit;		

?>