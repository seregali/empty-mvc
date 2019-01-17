<?
defined('_VALID') or die();

App::get()->title = "Забыли пароль? | ".TITLE;

$s = getParam($_GET, "s");
$e = getParam($_GET, "e");
?>

<div class="col-12 content">
	<h1>Забыли пароль?</h1>


<?
if ($s) {
	?>
	<div class="alert alert-success">
	<?
	switch ($s) {
		case 1:
			echo 'Мы отправили на указанный вами email новый пароль';
			break;		
		default:
			echo 'Ошибка сообщения';
			break;
	}
	?>
	</div>
	<?
}

if ($e) {
	?>
	<div class="alert alert-danger">
	<?
	switch ($e) {
		case 1:
			echo 'Данный email не найден в базе';
			break;			
		default:
			echo 'Не известная ошибка';
			break;
	}
	?>
	</div>
	<?
}
?>

<form name="forgot" method="post" action="forgot" class="col-xs-12 col-sm-6">
    
    <div class="form-group row">
	    <label class="col-4">Email</label>
	    <div class="col-6">
	    	<input type="email" required="" class="form-control" name="email" placeholder="Введите ваш email"><br/>
	    </div>
    </div>

    <div class="form-group row">
	    <label class="col-4"></label>
	    <div class="col-6">
	    	<button type="submit" class="btn btn-primary">Восстановить</button>
	    </div>
	</div>
</form>

</div>