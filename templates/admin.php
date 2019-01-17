<?
defined('_VALID') or die();
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?= App::get()->title ?></title>
    <base href="<?= URL ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

</head>
<body>


<?= App::get()->module('adminMenu') ?>

<div class="container">
<?= App::get()->mainbody ?>
</div>

<? 
App::get()->includeStatic('css');
App::get()->includeStatic('js');
?>
</body>
</html>
