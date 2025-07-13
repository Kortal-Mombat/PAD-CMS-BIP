<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    if (!isset($_SESSION['userData']) && !isset($_SESSION['userData']['login'])) {
		header('Location: /error-400');
		exit;
    }
?>