<?
defined('_VALID') or die();

class frontendController extends controller
{
    var $templates = Array(
        'index' => Array('frontend', 0)
    );

    function index()
    {
    	parent::view();
    }
}