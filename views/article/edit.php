<?
defined('_VALID') or die();

$id = getParam($_REQUEST, "id");
$parentId = getParam($_REQUEST, "parentId");

try {
    $article = new article(null, $id);
} catch (Exception $e) {
}

?>

<h3><? if ($article->id) { ?> Редактирование <? } else { ?> Добавление <? } ?>статьи</h3>
<form action="article/edit" method="post" id="addForm" enctype="multipart/form-data">

<input type="hidden" name="id" data-folder="article" value="<?= $article->id ?>" id="object-id"/>

<div class="form-group">
    <label>Заголовок</label>
    <input type="text" class="form-control" name="title" value="<?= $article->title ?>"/>
</div>

<div class="form-group">
    <label>Alias</label>
    <input type="text" class="form-control" name="alias" value="<?= $article->alias ?>"/>
</div>

<div class="form-group">
    <label>Родительская статья</label>
    <?
    $articles = article::getAll();
    ?>
    <select name="parentId" class="form-control">
        <option value="">..</option>
        <?
        foreach ($articles as $a) {
            ?>
            <option <?= $a->id == $article->parentId || $a->id == $parentId ? "selected=selected" : "" ?>
                value="<?= $a->id ?>">
                <?= $a->title ?> (<?= $a->alias ?>)
            </option>
        <?
        }
        ?>
    </select>
</div>

<div class="form-group">
    <label>Интро</label>
    <textarea name="intro" class="form-control summernoteText"><?= $article->intro ?></textarea>
</div>

<div class="form-group">
    <label>Текст</label>
    <textarea name="text" class="form-control summernoteText"><?= $article->text ?></textarea>
</div>

<div class="form-group">
    <label>Галерея</label>
    <div class="group">
        <span class="btn btn-success fileinput-button">
            <span>Загрузить изображения</span>
            <input id="fileupload" type="file" name="files[]" data-url="file/upload" multiple/>
        </span>
    </div>
    <div id="gallery-images">
        <?
            $article->getImages();

            if (count($article->images)) {
                foreach ($article->images as $image) {
                    ?>
                    <div class="gallery-image" id="image<?= $image->id ?>">
                        <input type="hidden" name="image[]" value="<?= $image->id ?>"/>
                        <img class="img-thumbnail" src="uploads/article/<?= $image->url ?>"/>
                        <span class="gallery-image-delete" data-id="<?= $image->id ?>">
                            <i>X</i>
                        </span>
                    </div>
                <?
                }  
            }
        ?>
    </div>
</div>

<div class="bottomPanel">
    <div class="container">
        <button class="btn btn-success">
            <i class="glyphicon glyphicon-ok"></i> 
            <span><?= $article->id ? 'Сохранить' : 'Создать' ?></span>
        </button>

         <button class="btn btn-primary" name="new" value="1">
            <i class="glyphicon glyphicon-check"></i> 
            <span>Сохранить и создать новую</span>
        </button>

         <a href="article/delete/<?= $article->id ?>" class="btn btn-danger item-delete">
            <i class="glyphicon glyphicon-trash"></i>
            <span>Удалить</span>
        </a>
    </div>
</div>

</form>