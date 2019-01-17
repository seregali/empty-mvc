<?
defined('_VALID') or die();
?>

<h3>Список статей</h3>

<?
$articles = article::getStructure();

if (count($articles)) {
    ?>
    <div class="row">
        <?
        foreach ($articles as $article) {
            ?>
            <div class="col-xs-12" data-id="<?= $article->id ?>">
                <a href="article/edit/<?= $article->id ?>">
                    <?= strlen($article->title) ? $article->title : "Пустой заголовок" ?>
                </a>
                <?
                $article->show();
                ?>
            </div>
        <?
        }
        ?>
    </div>
<?
} else {
    ?>
    <p>Нет статей</p>
<?
}
?>

<div class="bottomPanel">
    <div class="container">
        <a href="article/edit" class="btn btn-success">Добавить</a>
    </div>
</div>