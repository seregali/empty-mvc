<?
defined('_VALID') or die();

class user extends entity
{
    var $fullname = "";
    var $email = "";
    var $phone = "";
    var $password = "";
    var $type = 0;
    var $blocked = 1;
    var $code = "";
    var $dateRegister = 0;
    var $registrationIP = "";
    var $lastIP = "";

    function __construct($id = null, $email = null)
    {
        if ($id) {
            if (!$this->get($id))
                throw new Exception();

        } elseif ($email) {
            if (!$this->getByEmail($email))
                throw new Exception();
        }       
    }

    function getByEmail($email)
    {
        $query = "SELECT * FROM `user` WHERE (`email` = :email)";
        $sth = App::get()->db->query($query, Array("email" => $email), $this);

        if ($row = $sth->fetch()) {
            return true;
        }

        return null;
    }


    function activate($code)
    {
        $query = "SELECT * FROM `user` WHERE (`code` = :code)";
        $sth = App::get()->db->query($query, Array("code" => $code), $this);

        if ($row = $sth->fetch()) {
            $this->blocked = 0;
            $this->code = "";
            $this->save();

            return true;
        } else
            return false;
    }

    static function init()
    {
        session::purge();


        $sessionCookieName = session::sessionCookieName();

        if (isset($_COOKIE[$sessionCookieName])) {
            $sessionCookieValue = $_COOKIE[$sessionCookieName];
            $delete = true;
            try {
                $session = new session(null, $sessionCookieValue);
                $session->time = time()+604800;
                $session->save();

                try {
                    $me = new user($session->userid);
                    App::get()->me = $me;
                    App::get()->loggedIn = true;

                    $delete = false;
                } catch (Exception $e) {
                    echo "";
                }
            } catch (Exception $e) {
                echo "";

            }

            if ($delete) {
                App::get()->me = "";
                App::get()->loggedIn = false;
                setcookie($sessionCookieName, "", false, '/');
            }
        }
    }
}