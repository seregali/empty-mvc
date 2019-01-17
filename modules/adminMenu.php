<?
defined('_VALID') or die();

$url = getCurrentUrl();
$urls = explode('/', $url);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="">На сайт</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item <?= $urls[0] == 'article' ? ' active ' : '' ?>">
            <a href="article/viewlist" class="nav-link">Статьи</a>
        </li>
        <li class="nav-item <?= $urls[0] == 'meta' ? ' active ' : '' ?>">
            <a href="meta/viewlist" class="nav-link">Мета</a>
        </li>
        <li class="nav-item <?= $urls[0] == 'user' ? ' active ' : '' ?>">
            <a href="user/viewlist" class="nav-link">Пользователи</a>
        </li>
    </ul>
    <ul class="navbar-nav navbar-right">
        <?
        $email = explode("@", App::get()->me->email);
        ?>
        <li class="<?= $urls[0] == 'user' ? ' active ' : '' ?>"><a href="logout"
                                                                   class="logout nav-link"><span><?= $email[0] ?>
                    @</span>Выход</a></li>
    </ul>
  </div>
</nav>