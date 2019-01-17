<?
defined('_VALID') or die();

class adminController extends controller
{
    var $templates = Array(
        'index' => Array('admin', 2)
    );

    function index()
    {
    	redirect("article/viewlist");
    }
}