<?
defined('_VALID') or die();

class metaController extends controller
{
    var $templates = Array(
        'edit' => Array('admin', 2),
        'viewlist' => Array('admin', 2),
        'delete' => Array('admin', 2)
    );

    function viewlist()
    {
        parent::view();
    }

    function edit()
    {
        $id = getParam($_REQUEST, "id");

        if (isset($_POST['id'])) {
            $meta = new meta($id);

            getRequestObject($meta);

            $meta->save();

            redirect("meta/viewlist");
        } else {
            parent::view();
        }
    }

    function delete()
    {
        $id = getParam($_REQUEST, "id");

        if ($id) {
            $m = new meta($id);
            $m->delete();
        }

        redirect("meta/viewlist");
    }
}
