<?
defined('_VALID') or die();

$id = getParam($_REQUEST, "id", "");

$user = new user($id);
?>
<h3><? if ($article->id) { ?> Редактирование <? } else { ?> Добавление <? } ?>пользователя</h3>
<form action="user/edit" method="post" id="addMenuForm" enctype="multipart/form-data"> 
<input type="hidden" name="id" value="<?= $user->id ?>"/>

<div class="form-group">
    <label>E-mail</label>
    <input type="text" name="email" value="<?= $user->email ?>" class="form-control"/>
</div>

<div class="form-group">
    <label>Полное имя</label>
    <input type="text" name="fullname" value="<?= $user->fullname ?>" class="form-control"/>
</div>

<div class="form-group">
    <label>Админ?</label>
    <div class="checkbox">
        <label>
            <input type="radio" value="1" name="type" <? if ($user->type) { ?>
                   checked="checked" <? } ?>>&nbsp;Да
        </label>
        <label>
            <input type="radio" value="0" name="type" <? if (!$user->type) { ?>
                   checked="checked" <? } ?>>&nbsp;Нет
        </label>
    </div>
</div>


<div class="form-group">
    <label>Заблокирован?</label>
     <div class="checkbox">
        <label>
            <input type="radio" value="1" name="blocked" <? if ($user->blocked) { ?>
                   checked="checked" <? } ?>>&nbsp;Да
        </label>
        <label>
            <input type="radio" value="0" name="blocked" <? if (!$user->blocked) { ?>
                   checked="checked" <? } ?>>&nbsp;Нет
        </label>
    </div>
</div>

<div class="form-group">
    <label>Пароль</label>
    <input type="text" name="password" value="" class="form-control"/>
</div>

<div class="bottomPanel">
    <div class="container">
        <button class="btn btn-success">
            <i class="glyphicon glyphicon-ok"></i> 
            <span><?= $user->id ? 'Сохранить' : 'Создать' ?></span>
        </button>

         <a href="user/delete/<?= $user->id ?>" class="btn btn-danger item-delete">
            <i class="glyphicon glyphicon-trash"></i>
            <span>Удалить</span>
        </a>
    </div>
</div>

</form>