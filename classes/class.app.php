<?
defined('_VALID') or die();

//MVC APP
class App
{
    static private $instance = null;

    public $route = "";
    public $controller = "";
    public $action = "";

    public $template = "";

    public $db = null;
    public $mysqldb = null;
    public $user = null;
    public $basePath = "";

    public $session = null;
    public $me = null;
    public $loggedIn = false;

    public $queries = Array();
    public $url = "";

    public $title = "";
    public $mainbody = "";

    public $content = "";

    public $metakeys = "";
    public $metadesc = "";

    private $objectCache = Array();

    public $css = Array();
    public $js = Array();

    static function get()
    {
        if (self::$instance == null) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->me = null;
        $this->loggedIn = false;
        $this->url = getCurrentUrl();
    }

    private function __clone()
    {
    }

    function mainbody()
    {
        $controller = $this->controller;
        $action = $this->action;

        if (!$controller || !$action)
            redirect('error/notfound');

        $class = $controller . "Controller";

        //Если контроллера не существует
        if (!class_exists($class))
            redirect('error/notfound');

        $controllerObject = new $class();

        if (!is_callable(array($controllerObject, $action))) {
            redirect('error/notfound');
        }

        switch ($controllerObject->templates[$action][1]) {
            //Вообще не показывать страницу...пригодится
            case -2:
                redirect('error/notfound');
                break;
            // Не пускать залогиненых на страницы связанные с регистрацией и т.д.
            case -1:
                if ($this->loggedIn)
                    redirect('admin');
                break;
            // Общие страницы
            case 0:
                break;
            // Только залогиненые
            case 1:
                if (!$this->loggedIn && $this->blocked != 1)
                    redirect('login');
                break;
            // Только админ
            case 2:
                if (!$this->loggedIn)
                    redirect('login');

                if ($this->me->type != 1)
                    redirect('error/notfound');
                break;
        }

        $controllerObject->$action();

        $this->template = $controllerObject->templates[$action][0];

        //Adding default static
        $this->addDefaultStatic();

        //Adding custom static from controller info
        if (isset($controllerObject->templates[$action][2])) {
            $this->addStatic($controllerObject->templates[$action][2]);
        }
    }

    static function module($name)
    {
        include (ABS_PATH . "/modules/" . $name . ".php");
    }

    function checkObjectCache($id)
    {
        if (ALLOW_OBJECT_CACHING)
            if (isset(App::get()->objectCache[$id]))
                return true;

        return false;
    }

    function getObjectFromCache($id)
    {
        if (isset(App::get()->objectCache[$id]))
            return App::get()->objectCache[$id];

        return false;
    }

    function setObjectToCache($object)
    {
        if (!$object->id || !ALLOW_OBJECT_CACHING)
            return false;

        App::get()->objectCache[$object->id] = $object;
        return true;
    }

    static function initTemplate()
    {
        if (App::get()->template)
            require_once("templates/" . App::get()->template . ".php");
    }

    //Static that has the same name as current template - admin.css, frontend.js and with controller/action
    public function addDefaultStatic()
    {
        if (!$this->template)
            return;

            $cssTemplateFileName = "static/css/" . $this->template . ".css";

        if (file_exists(ABS_PATH . "/" . $cssTemplateFileName))
            $this->addStatic($cssTemplateFileName, "css");

            $jsTemplateFileName = "static/js/" . $this->template . ".js";
        

        if (file_exists(ABS_PATH . "/" . $jsTemplateFileName))
            $this->addStatic($jsTemplateFileName);

        $controller = $this->controller;
        $action = $this->action;
        
            $actionJSFileName = "static/js/" . $controller . "/" . $action . ".js";

        if (file_exists(ABS_PATH . "/" . $actionJSFileName))
            $this->addStatic($actionJSFileName, "js");
    }

    public function addStatic($paths)
    {
        if (!is_array($paths)) {
            $paths = Array($paths);
        }

        foreach ($paths as $path) {
            $pathInfo = pathinfo($path);
            $extension = $pathInfo["extension"];
            if ($extension != "css" && $extension != "js")
                continue;
            $typeArray = &$this->$extension;
            if (!in_array($path, $typeArray))
                $typeArray[] = $path;
        }
    }

    public function includeStatic($exp=null)
    {
        if (!$exp) {
           foreach ($this->css as $css) {
            ?>
            <link href="<?= $css ?>" rel="stylesheet" type="text/css"/>
            <?
            }
            foreach ($this->js as $js) {
                ?>
            <script type="text/javascript" src="<?= $js ?>"></script>
            <?
            } 
        } else {
            if ($exp == 'js') {
                foreach ($this->js as $js) {
                    ?>
                <script type="text/javascript" src="<?= $js ?>"></script>
                <?
                } 
            }

            if ($exp == 'css') {
                foreach ($this->css as $css) {
                ?>
                <link href="<?= $css ?>" rel="stylesheet" type="text/css"/>
                <?
                }
            }
        }
    }
}
