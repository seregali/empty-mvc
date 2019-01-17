<?
defined('_VALID') or die();
?>

<div class="login">
    <?

    if (App::get()->loggedIn) {
        $me = App::get()->me;
        ?>
        <?= $me->firstname ?>&nbsp;<?= $me->lastname ?>
        <a href="logout">выход</a>
        <?
    }
    else {
        ?>
        <a href="login">вход</a>
        <a href="registration">регистрация</a>
        <?
    }

    ?>
</div>

