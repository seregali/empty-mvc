<?
defined('_VALID') or die();

class session extends entity
{
    var $email = "";
    var $time = 0;
    var $userid = "";
    var $cookieValue = "";

    function __construct($id = null, $cookieValue = null)
    {
        if ($id) {
            if (!$this->get($id))
                throw new Exception();

        } elseif ($cookieValue) {
            if (!$this->getByCookieValue($cookieValue))
                throw new Exception();
        }
    }

    static function purge()
    {
        $query = "DELETE FROM `session` WHERE (`time` < :time)";
        App::get()->db->query($query, Array("time" => time()));
    }

    static function sessionCookieName()
    {
        $browser = @$_SERVER['HTTP_USER_AGENT'];
        $hash = $_COOKIE['PHPSESSID'];
        return md5('site'.$browser.$hash);
    }

    static function sessionCookieValue($id = null)
    {
        $browser = @$_SERVER['HTTP_USER_AGENT'];
        $hash = $_COOKIE['PHPSESSID'];
        
        $salt = makePassword(29);
        $crypt = crypt(md5($id.$browser.$hash), $salt); 
        return $crypt;
    }

    static function sessionCookieClear($id = null)
    {

        $query = "DELETE FROM `session` WHERE `userid` = :id";
        $sth = App::get()->db->query($query, Array("id" => $id));
    }

    function getByCookieValue($cookieValue)
    {
        if (!$cookieValue)
            return false;

        $query = "SELECT * FROM `session` WHERE (`cookieValue` = :cookieValue)";

        $sth = App::get()->db->query($query, Array("cookieValue" => $cookieValue), $this);
        if ($row = $sth->fetch()) {
            return true;
        }

        return false;
    }
}