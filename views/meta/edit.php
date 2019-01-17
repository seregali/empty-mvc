<?
defined('_VALID') or die();

$id = getParam($_REQUEST, "id");
$meta = new meta($id);
?>
<h3><? if ($meta->id) { ?> Редактирование <? } else { ?> Добавление <? } ?>метаданные</h3>
<form action="meta/edit" method="post" id="addMenuForm" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $meta->id ?>"/>

<div class="form-group">
    <label>URL</label>
    <input type="text" name="url" value="<?= $meta->url ?>" class="form-control"/>
</div>

<div class="form-group">
    <label>Заголовок страницы</label>
    <input type="text" name="title" value="<?= $meta->title ?>" class="form-control"/>
</div>

<div class="form-group">
    <label>Мета описание</label>
    <textarea name="metadesc" class="form-control"><?= $meta->metadesc ?></textarea>
</div>

<div class="form-group">
    <label>Мета ключи</label>
    <textarea name="metakeys" class="form-control"><?= $meta->metakeys ?></textarea>
</div>

<div class="bottomPanel">
    <div class="container">
        <button class="btn btn-success">
            <i class="glyphicon glyphicon-ok"></i> 
            <span><?= $meta->id ? 'Сохранить' : 'Создать' ?></span>
        </button>

         <a href="meta/delete/<?= $meta->id ?>" class="btn btn-danger item-delete">
            <i class="glyphicon glyphicon-trash"></i>
            <span>Удалить</span>
        </a>
    </div>
</div>
</form>