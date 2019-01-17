<?
defined('_VALID') or die();

class image extends entity
{
    var $objectId = "";
    var $url = "";
    var $desc = "";
    var $folder = "";
    var $ordering = 0;

    function __construct($id = null)
    {
        if ($id)
            if (!$this->get($id))
                throw new Exception();
    }

    function delete()
    {
        $path = ABS_PATH . "/uploads/".$this->folder."/" . $this->url;
        if (is_file($path)) {
            unlink($path);
        }

        $query = "DELETE FROM `image` WHERE `id` = :id";
        App::get()->db->query($query, array("id" => $this->id));
    }
}