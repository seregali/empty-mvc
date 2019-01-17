<?
defined('_VALID') or die();

$viewdata = Array();

class controller
{
    public $viewdata = Array();

    function view()
    {
        $conrtollerClass = get_class($this);
        $entityClass = substr($conrtollerClass, 0, strlen($conrtollerClass) - 10);

        if (file_exists(ABS_PATH . '/views/' . $entityClass . '/' . App::get()->action . '.php')) {

        	require_once ABS_PATH . '/views/' . $entityClass . '/' . App::get()->action . '.php';
        	
        } else {
        	redirect('error/notfound');
        }
    }
}