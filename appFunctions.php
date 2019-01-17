<?
define("ALLOW_RAW", 2);

spl_autoload_register(function($class) {
    try {
        if (strpos($class, "Controller") === false) {
            if (file_exists(ABS_PATH . "/classes/class." . strtolower($class) . ".php")) {
                require_once ABS_PATH . "/classes/class." . strtolower($class) . ".php";   
            } else {
               throw new Exception();
            }
        } else {  
            if (file_exists(ABS_PATH . "/controllers/" . $class . ".php")) {
                require_once ABS_PATH . "/controllers/" . $class . ".php";
            } else {
                throw new Exception();
            }
        }   
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return true;
});

function getParam(&$arr, $name, $default = null, $mask = 0)
{
    if (!isset($arr[$name]))
        return $default;

    $result = $arr[$name];

    if (!is_string($result))
        return $result;

    $result = trim($result);

    if ($mask == ALLOW_RAW)
        return $result;

    $result = htmlspecialchars(strip_tags($result), ENT_QUOTES | 48);

    return $result;
}

function getRequestObject(&$object, $allowRawFields = Array(), $skipFields = Array())
{
    foreach (get_class_vars(get_class($object)) as $name => $value) {
        if ($name == "id" || in_array($name, $skipFields))
            continue;

        if (isset($_REQUEST[$name])) {
            if (strpos($name, 'date') === 0)
                $object->$name = dateParse(getParam($_REQUEST, $name));
            elseif (in_array($name, $allowRawFields)) {
                $object->$name = getParam($_REQUEST, $name, null, ALLOW_RAW);
            } else {
                $object->$name = getParam($_REQUEST, $name);
            }
        }
    }
}

function getRequestFiles(&$object, $deleteFileFieldName = null)
{
    $className = get_class($object);

    $path = ABS_PATH . "/uploads/" . $className . "/";

    if ($deleteFileFieldName) {
        if (file_exists($path . $object->$deleteFileFieldName)) {
            if (unlink($path . $object->$deleteFileFieldName))
                echo "File deleted";
        }
        $object->$deleteFileFieldName = null;
    }

    foreach (get_class_vars($className) as $name => $value) {
        if (isset($_FILES[$name])) {
            if ($_FILES[$name]['name'] != "") {

                if (file_exists($path . $object->$name)) {
                    if ($object->$name != "")
                        if (unlink($path . $object->$name))
                            echo "Old file deleted";
                }

                $path_parts = pathinfo($_FILES[$name]['name']);
                $object->$name = time() . makePassword() . "." . $path_parts["extension"];
                copy($_FILES[$name]['tmp_name'], $path . $object->$name);
            }
        }
    }
}

function redirect($url)
{
    $url = URL . "/" . $url;
    if (headers_sent()) {
        echo "<script type='text/javascript'>document.location.href='$url';</script>\n";
    } else {
        @ob_end_clean(); // clear output buffer
        header('HTTP/1.1 301 Moved Permanently');
        header("Location: " . $url);
    }
    exit();
}

function dateParse($date)
{
    $dt = date_parse($date);
    return mktime($dt['hour'], $dt['minute'], $dt['second'], $dt['month'], $dt['day'], $dt['year']);
}

function dateParseBad($date)
{
    $part = explode('-', $date);
    return mktime(0, 0, 0, $part[1], $part[2], $part[0]);
}

function makePassword($length = 8)
{
    $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $makepass = '';
    mt_srand(10000000 * (double)microtime());
    for ($i = 0; $i < $length; $i++) $makepass .= $salt[mt_rand(0, 61)];
    return $makepass;
}

function makeAlias($length = 8)
{
    $salt = "0123456789";
    $makepass = '';
    mt_srand(10000000 * (double)microtime());
    for ($i = 0; $i < $length; $i++) $makepass .= $salt[mt_rand(0, 9)];
    return $makepass;
}

function isValidEmail($email)
{
    return preg_match("/\\A(?:^([a-z0-9][a-z0-9_\\-\\.\\+]*)@([a-z0-9][a-z0-9\\.\\-]{0,63}\\.([a-z]{2,4}))$)\\z/i", $email);
}

function superHash($seed)
{
    return md5(md5($seed));
}

function guid()
{
    if (function_exists('com_create_guid')) {
        $g = strtolower(com_create_guid());
        return substr(substr($g, 1), 0, strlen($g) - 2);
    } else {
        mt_srand((double)microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid =
            substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
        return $uuid;
    }
}

function map($src, &$dest, $addSlashes = false)
{
    $srcVars = get_object_vars($src);
    $destVars = get_object_vars($dest);

    foreach ($srcVars as $name => $value) {
        if (!array_key_exists($name, $destVars))
            continue;

        $dest->$name = $addSlashes ? addslashes($src->$name) : $src->$name;
    }
}

if (!function_exists('get_called_class')):

    function get_called_class()
    {
        $bt = debug_backtrace();
        $lines = file($bt[1]['file']);
        preg_match('/([a-zA-Z0-9\_]+)::' . $bt[1]['function'] . '/',
            $lines[$bt[1]['line'] - 1],
            $matches);
        return $matches[1];
    }

endif;

function n($var)
{
    ?>
<pre>
    <? print_r($var); ?>
			</pre>
<?
}

function getRusFullDate($date)
{
    return date("d", $date)." ".getRusMonth(date("m", $date))." ".date("Y", $date);
}

function getRusMonth($month)
{
    if ($month > 12 || $month < 1) return FALSE;
    $aMonth = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    return $aMonth[$month - 1];
}


function generateFileName($name)
{
    $parts = pathinfo($name);
    return time() . "_" . makePassword(8) . "." . $parts["extension"];
}

function price($value) {
    $count = strlen($value);

    $price = number_format($value, 0, ',', ',');

    return $price;
}

function getAlias($title)
{
    if (empty($title))
        return false;


    $tr = array(
        "А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
        "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
        "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
        "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
        "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
        "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "yi", "Ь" => "",
        "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
        "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
        "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
        "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
        "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
        "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
        "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
        " " => "-", "!" => "", "?" => "", "," => "", "." => "", "\"" => ""
    );
    return strtr($title, $tr);
}

function getCurrentUrl()
{
    $url = $_SERVER['REQUEST_URI'];
    $url = substr($url, 1);

    return $url;
}

function isJSON($string) {
    return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
}