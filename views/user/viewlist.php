<?
defined('_VALID') or die();
?>
<h3>Список пользователей</h3>

<?
$users = user::getAll();

if (count($users)) {
    ?>
    <table class="table table-striped">
        <?
        foreach ($users as $user) {
            ?>
            <tr>
                <td>
                    <a href="user/edit/<?= $user->id ?>">
                        <?= strlen($user->email) ? $user->email : "<span class='muted'>Пустой заголовок</span>" ?>
                    </a>
                </td>
                <td>
                    <?= $user->type ? 'Администратор' : '' ?>
                </td>
            </tr>
        <?
        }
        ?>
    </table>
<?
}
?>

<div class="bottomPanel">
    <div class="container">
        <a href="user/edit" class="btn btn-success">Добавить</a>
    </div>
</div>