<?
defined('_VALID') or die();

class imageController extends controller
{
    var $templates = Array(
        'add' => Array('empty', 0),
        'setordering' => Array('empty', 0),
        'delete' => Array('empty', 0)
    );

    function add()
    {
        $image = new image();

        getRequestObject($image);
        $image->save();

        echo $image->id;
    }

    function setordering()
    {
        $ids = getParam($_REQUEST, "ids");
        foreach ($ids as $key => $id) {
            $image = new image($id);
            $image->ordering = $key;
            $image->save();
        }
    }

    function delete()
    {
        $id = getParam($_POST, "id");

        if (!$id)
            return;

        $image = new image($id);
        $image->delete();
    }
}