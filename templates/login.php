<?
defined('_VALID') or die();
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?= App::get()->title ?></title>
    <base href="<?= URL ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" href="static/images/admin/favicon.png"/>
    <meta name="robots" content="noindex, nofollow"/>
    <? 
    App::get()->addStatic('dist/modules/bootstrap.min.css');
    App::get()->addStatic('dist/modules/bootstrap.min.js');
    ?>
</head>
<body>

<div class="container login">
    <?= App::get()->mainbody ?>
</div>

<?
App::get()->includeStatic('css');
?>

</body>
</html>
