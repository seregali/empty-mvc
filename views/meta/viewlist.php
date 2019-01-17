<?
defined('_VALID') or die();
?>

<h3>Мета данные</h3>
<?
$metas = meta::getAll();

if (count($metas)) {
    ?>
    <table class="table table-striped">
        <?
        foreach ($metas as $meta) {
            ?>
            <tr>
                <td>
                    <a href="meta/edit/<?= $meta->id ?>"><?= strlen($meta->title) ? $meta->title : "<span class='muted'>Пустой заголовок</span>" ?></a>
                </td>
                <td>url: <?= $meta->url ?></td>
            </tr>
        <?
        }
        ?>
    </table>
<?
} else {
    ?>
    <p>Нет метаданных</p>
<?
}
?>

<div class="bottomPanel">
    <div class="container">
        <a href="meta/edit" class="btn btn-success">Добавить</a>
    </div>
</div>