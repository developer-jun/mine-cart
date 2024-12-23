<?php 
error_reporting(E_ALL);
session_start();

if (!defined("DB_SERVER") || !define("DB_NAME")){
	define("DB_SERVER", "localhost");
	define("DB_NAME", "beta");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
}

define("APP_DIR", dirname(__FILE__));
define("APP_URL", dirname($_SERVER['PHP_SELF']));
define("PUBLIC_DIR", APP_URL.'/public/');

define("CSRF_TOKEN_NAME", 'csrf_protection_token');
define("CURRENCY", "$");
define("DATE_FORMAT", "d-m-Y");
define("VALID_UNTIL", 7 * 86400); // cookie default 7 days

require_once('includes/autoload.php');
require_once APP_DIR.'/vendor/autoload.php';