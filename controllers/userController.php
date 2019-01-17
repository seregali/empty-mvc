<?
defined('_VALID') or die();

class userController extends controller
{
    var $templates = Array(
        'login' => Array('inner', -1),
		'logout' => Array('empty', 1),
        'edit' => Array('admin', 2),
        'viewlist' => Array('admin', 2),
        'delete' => Array('admin', 2),
        'registration' => Array('inner', -1),
        'complete' => Array('inner', -1),
        'settings' => Array('inner', 1),
        'cabinet' => Array('inner', 1),
        'forgot' => Array('inner', -1)
    );

    function cabinet()
    {
        parent::view();
    }

    function forgot()
    {
        if (count($_POST)) {
            $email = getParam($_POST, "email");

            $u = new user();
            if ($u->getByEmail($email)) {

                $password = makePassword(8);

                $salt = makePassword(29);

                $crypt = crypt(md5($password), $salt);

                $u->password = $crypt.":".$salt;
                $u->save();


                $to = $u->email;

                $subject = "Новый пароль | ".TITLE;
                $message = "Новый пароль: ".$password;

                mailer::mail($to, $subject, $message);

                redirect("forgot?s=1");

            } else {
                redirect("forgot?e=1");
            }
        }

        parent::view();
        
    }

    function settings()
    {

        if (count($_POST)) {

            $password = getParam($_POST, "password");
            $newpassword = getParam($_POST, "newpassword");
            $repassword = getParam($_POST, "repassword");

            $email = getParam($_POST, "email");


            $fullname = getParam($_POST, "fullname");
            $phone = getParam($_POST, "phone");


            $me = new user(App::get()->me->id);

            $me->phone = $phone;
            $me->fullname = $fullname;

            $me->save();

            // Подтверждение нового email
            if ($email != App::get()->me->email) {
                $u = new user();
                if (!$u->getByEmail($email)) {
                    if (isValidEmail($email)) {
                        $me->email = $email;

                        $me->save();

                        redirect("settings?s=1");
                    } else {
                        redirect("settings?e=2");
                    }
                } else {
                    redirect("settings?e=1");
                }
            }

            // Изменять пароль если введен
            if (!empty($password)) {

                list($hash, $salt) = explode(":", $me->password);

                $crypt = crypt(md5($password), $salt);

                if ($crypt != $hash) {
                    redirect("settings?e=3");
                } else {

                    if (!empty($newpassword)) {
                        if ($newpassword != $repassword) {
                            redirect("settings?e=5");
                        } else {

                            $salt = makePassword(29);
                            $newcrypt = crypt(md5($newpassword), $salt);

                            $me->password = $newcrypt.":".$salt;
                            $me->save();

                            redirect("settings?s=2");

                        }
                    } else {
                        redirect("settings?e=4");
                    }

                }
            }

            redirect("settings?s=3");
        }

        parent::view();
    }

    function complete()
    {
        parent::view();
    }

    function registration()
    {

        if (count($_POST)) {

            $email = getParam($_POST, "email");
            $fullname = getParam($_POST, "fullname");
            $password = getParam($_POST, "password");
            $repassword = getParam($_POST, "repassword");

            $u = new user();

            $result_json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6LctESwUAAAAAKtcGV1tzM7hs39vKQnOAVInRPcQ&response='.$_POST['g-recaptcha-response']);
            
            $result = json_decode($result_json);


            if (!$result->success) {
                redirect("registration?m=2");
            }

            if (!$u->getByEmail($email)) {
                $u->fullname = $fullname;
                $u->email = $email;

                $u->type = 0;
                $u->manager = 0;
                $u->operator = 0;
                $u->blocked = 0;
                $u->code = makePassword(16);
                $u->dateRegister = time();
                $u->registrationIP = $_SERVER['REMOTE_ADDR'];
                $u->lastIP = $_SERVER['REMOTE_ADDR'];

                if (!empty($password)) {

                    if ($password == $repassword) {
                        $salt = makePassword(29);
                        $crypt = crypt(md5($password), $salt); 

                        $u->password = $crypt . ":" . $salt;
                    } else {
                        redirect("registration?m=3");
                    }
                    
                }

                $u->save();

                $to = $u->email;
                $subject = "Регистрация на сайте | ".TITLE;

                $link = URL."/activation/".$u->code;

                $message = "Вы успешно зарегистрировались. Зайдите на сайт и авторизуйтесь.";

                mailer::mail($to, $subject, $message);

                redirect("registration/complete");

            } else {
                redirect("registration?m=1");
            }

        }

        parent::view();
    }

    function login()
    {
        global $messages;
        $messages = Array();

        $email = getParam($_POST, 'email');
        $passwd = getParam($_POST, 'passwd');

        if (!$email || !$passwd) {
            if (isset($_POST["email"]))
                redirect("login?m=1");
        } else {
            
            try {
                $u = new user(null, $email);
                if ($u->blocked == 1) {
                    redirect("login?m=2");
                } else {
                    // Тут пароль проверяем
                    list($hash, $salt) = explode(":", $u->password);


                    $cryptpass = crypt(md5($passwd), $salt);

                    if ($hash != $cryptpass) {
                        redirect("login?m=3");
                    } else {
                        
                        // initialize session data
                        $s = new session();

                        $sessionCookieName = session::sessionCookieName();
                        $sessionCookieValue = session::sessionCookieValue($sessionCookieName);

                        $s->cookieValue = $sessionCookieValue;
                        $s->email = $u->email;
                        $s->userid = $u->id;
                        $s->time = time()+604800;

                        $s->save();

                        setcookie($sessionCookieName, $sessionCookieValue, time() + USER_SESSION_LENGTH, '/');

                        $u->lastIP = $_SERVER['REMOTE_ADDR'];
                        $u->save();


                        // Админа переводим в админку
                        if ($u->type)
                           redirect("admin");
                        else
                            redirect("myorders");
                    }
                }
            } catch (Exception $e) {
                $messages["input"] = "Неверное сочетание email/пароль";
            }
        }

        parent::view();
    }

    function logout()
    {
        $sessionCookieName = session::sessionCookieName();
		
		if (isset($_COOKIE[$sessionCookieName])) {

            $sessionCookieValue = $_COOKIE[$sessionCookieName];

            try {
                $session = new session(null, $sessionCookieValue);
                $session->delete();
				setcookie($sessionCookieName, "", false, '/');
             
            } catch (Exception $e) {
				echo "";
            }
        }

        redirect("");
    }

    function viewlist()
    {
        parent::view();
    }

    function delete()
    {
        $id = getParam($_REQUEST, "id");
        $user = new user($id);

        if ($id == App::get()->me->id)
            redirect("user/viewlist");
        else
            $user->delete();

        redirect("user/viewlist");
    }

    function edit()
    {
        if (isset($_POST["id"])) {
            $id = getParam($_REQUEST, "id");
            $user = new user($id);

            getRequestObject($user, Array('email', 'fullname'), Array("password"));

           


            if ($id != App::get()->me->id) {
                $user->type = getParam($_REQUEST, "type");
                $user->blocked = getParam($_REQUEST, "blocked");
            }

            $password = getParam($_REQUEST, "password");

            if (!empty($password)) {
                $salt = makePassword(29);
                $crypt = crypt(md5($password), $salt); 

                $user->password = $crypt . ":" . $salt;
            }

            $user->save();

            redirect("user/edit/".$user->id);
        } else {
            parent::view();
        }
    }
}