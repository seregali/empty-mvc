<?
defined('_VALID') or die();

class errorController extends controller
{
    var $templates = Array(
        'notfound' => Array('inner', 0)
    );

    function notfound()
    {
        parent::view();
    }
}