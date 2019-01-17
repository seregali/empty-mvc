<?

define('_VALID', 1);

setlocale(LC_CTYPE, 'C');
mb_internal_encoding('UTF-8');

session_start();

require_once('config.php');
require_once('appFunctions.php');

//utm_source=instagram&utm_medium=cpc&utm_campaign=sale30

$App = App::get();
//Коннект к базе
// $App->db = new Database();
$App->db = new mysqldatabase(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$App->db->query("SET NAMES 'utf8';");

$App->title = TITLE;
$App->metakeys = "";
$App->metadesc = TITLE;

$route = strtolower(getParam($_REQUEST, 'route', 'frontend/index'));

list($controller, $action) = explode('/', $route);

if (trim($action) == "")
    $action = "index";

$App->route = $route;
$App->controller = $controller;
$App->action = $action;

//Инициализируем пользователя
user::init();


//Запиливаем мету
meta::init();

$App->addStatic("static/libs/normalize.css");
$App->addStatic("static/libs/jquery-ui/jquery-ui.min.css");
$App->addStatic("static/libs/bootstrap/bootstrap.min.css");
$App->addStatic("static/libs/jquery-3.3.1.min.js");
$App->addStatic("static/libs/jquery-ui/jquery-ui.min.js");
$App->addStatic("static/libs/bootstrap/popper.min.js");
$App->addStatic("static/libs/bootstrap/bootstrap.min.js");

//Загружаем основное содержимое
ob_start();
$App->mainbody();
$App->mainbody = ob_get_contents();
ob_end_clean();

//Загружаем шаблон
$App::initTemplate();