<?
defined('_VALID') or die();

define("ABS_PATH", $_SERVER['DOCUMENT_ROOT']);
define("URL", "http://" . $_SERVER['SERVER_NAME']);
define("USER_SESSION_LENGTH", 60 * 60 * 24 * 7);
define("ARTICLES_PER_PAGE", 10);

define("DB_HOST" , "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "p123");

define("TITLE", "Новый сайт");

define("ALLOW_OBJECT_CACHING", true);