<?
defined('_VALID') or die();

class meta extends entity
{
    var $url = "";
    var $title = "";
    var $metadesc = "";
    var $metakeys = "";

    function __construct($id = null)
    {
        if ($id) {
            parent::get($id);
        }
    }

    static function init()
    {
        $App = App::get();
        $currentUrl = getCurrentUrl();
        $meta = meta::getByUrl($currentUrl);

        if ($meta) {
            $App->title = $meta->title;
            $App->metadesc = $meta->metadesc;
            $App->metakeys = $meta->metakeys;
        }
    }

    static function getByUrl($url)
    {
        $query = "SELECT * FROM `meta` WHERE (`url` = :url)";
        if ($sth = App::get()->db->query($query, Array("url" => $url))) {
            if ($row = $sth->fetchObject("meta")) {
                return $row;
            }
        }
        return false;
    }
}