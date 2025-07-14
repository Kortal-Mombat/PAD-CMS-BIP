<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['userData']) || !isset($_SESSION['userData']['login'])) {
      header('Location: /error-400');
      exit;
    }
    if (!defined('DS')) {
      define('DS', DIRECTORY_SEPARATOR);
    }
    if (!defined('CMS_BASE')) {
      define( 'CMS_BASE', dirname(__FILE__) );
    }
    if (!defined('CMS_ROOT')) {
      $parts = explode( DS, CMS_BASE );
      array_pop( $parts );
      define( 'CMS_ROOT', implode( DS, $parts ) ); 
    }
    include_once ( CMS_ROOT . DS . 'includes' . DS . 'config.php' );
    include_once ( CMS_ROOT . DS . 'includes' . DS . 'db.php' );
    include_once ( CMS_ROOT . DS . 'includes' . DS . 'settings.php' );
    include_once ( CMS_ROOT . DS . 'includes' . DS . 'functions.php' );
    if (!check_login_user()) {
      header('Location: /error-401');
      exit;
    }

?>