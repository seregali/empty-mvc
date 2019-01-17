<?
defined('_VALID') or die();

$alias = getParam($_GET, "alias");

$article = new article($alias);
App::get()->title = $article->title." | ".TITLE;
?>

<h1><?= $article->title ?></h1>
<p><?= $article->intro ?></p>
<p><?= $article->text ?></p>