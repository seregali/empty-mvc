<?
defined('_VALID') or die();

$m = getParam($_GET, "m");

App::get()->title = "Регистрация | ".TITLE;
?>

<div class="col-12 content">
	<h1>Регистрация</h1>

<?
if (isset($m)) {

	?>
	<div class="alert alert-danger">
	<?
	switch ($m) {
		case 1:
			echo 'На данный email, уже зарегистрирован аккаунт.';
			break;
		case 2:
			echo 'Не прошли проверку "Я не робот", попробуйте еще раз.';
			break;
		case 3:
			echo 'Пароли не совпадают.';
			break;
		default:
			echo 'Не известная ошибка.';
			break;
	}
	?>
	</div>
	<?
}
?>

<div class="col-xs-12 col-sm-8">
	<form name="registration" method="post" action="registration">
	    
	    <div class="form-group row">
	    	<label class="col-4">Email</label>
	    	<div class="col-8">
	    		<input type="email" required="" name="email" class="form-control" placeholder="Введите свой email"/>
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Имя</label>
	    	<div class="col-8">
	    		<input type="text" required="" name="fullname" class="form-control" placeholder="Введите свое имя">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Пароль</label>
	    	<div class="col-8">
	    		<input type="password" required="" name="password" pattern=".{6,}" title="Минимум 6 символов" class="form-control" placeholder="Введите свой пароль">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Подтвердите пароль</label>
	    	<div class="col-8">
	    		<input type="password" required="" name="repassword" class="form-control" placeholder="Подтвердите свой пароль">
	    	</div>
	    </div>

	    <div class="g-recaptcha" data-sitekey="6LfydiQUAAAAAOZEL_JGeDyR9sfWBnmmuiWRWY3d"></div>

	    <div class="form-group row">
	    	<div class="col-12">
	    		<button type="submit" class="btn btn-success">Зарегистрироваться</button> Вы соглашаетесь с <a href="agreement" target="_blank">пользовательским соглашением</a>
	    	</div>
	    </div>
	</form>
</div>

</div>