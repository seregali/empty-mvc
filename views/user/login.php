<?
defined('_VALID') or die();
global $messages;

$url =  $_SERVER['HTTP_REFERER'];

$m = getParam($_GET, "m");

$urls = explode("/", $url, 4);
?>

<div class="col-12 content">
    <h1>Авторизация</h1>


    <?
    if (isset($m)) {
        ?>
        <div class="alert alert-danger">
            <?
            switch ($m) {
                case 1:
                    echo 'Введите логин и пароль';
                    break;
                case 2:
                    echo 'Аккаунт не активирован';
                    break;
                case 3:
                    echo 'Неверное сочетание логин/пароль';
                    break;
                default:
                    echo 'Не известная ошибка';
                    break;
            }
            ?>
        </div>
        <?
    }
    ?>

    <div class="col-xs-12 col-sm-4">
        <form name="login" method="post" action="login"> 
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="" autofocus class="form-control"/>
            </div>

            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="passwd" value="" class="form-control"/>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Войти</button> <a href="forgot" style="padding-left: 20px;">Забыли пароль?</a>
            </div>
        </form>
    </div>

</div>