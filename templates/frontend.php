<?
defined('_VALID') or die();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= App::get()->title ?></title>
    <base href="<?= URL ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="<?= App::get()->metadesc ?>"/>
    <meta name="keywords" content="<?= App::get()->metakeys ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?
    App::get()->includeStatic('css');
    ?>
</head>
<body>

<?= App::get()->module('header') ?>

<?= App::get()->mainbody ?>

<?= App::get()->module('footer') ?>

<?
App::get()->includeStatic('js');

?>
</body>
</html>