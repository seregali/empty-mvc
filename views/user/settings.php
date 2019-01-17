<?
defined('_VALID') or die();

App::get()->title = "Настройки | ".TITLE;

$cities = city::getAll(true, 'title', 'ASC');

$s = getParam($_GET, "s");
$e = getParam($_GET, "e");
?>

<div class="col-12 content">
	<h1>Настройки</h1>

	<?
	if ($s) {
		?>
		<div class="col-12 alert alert-success">
		<?
		switch ($s) {
			case 1:
				echo 'Email обновлен';
				break;
			case 2:
				echo 'Пароль обновлен';
				break;
			case 3:
				echo 'Информация обновлена';
				break;		
			default:
				echo 'Данные изменены';
				break;
		}
		?>
		</div>
		<?
	}

	if ($e) {
		?>
		<div class="col-12 alert alert-danger">
		<?
		switch ($e) {
			case 1:
				echo 'Данный email уже есть базе';
				break;
			case 2:
				echo 'Email введен в не правильном формате. Пример a.bc_123@kalmar.kz';
				break;
			case 3:
				echo 'Неверный пароль';
				break;
			case 4:
				echo 'Введите новый пароль';
				break;
			case 5:
				echo 'Пароли не совпадают';
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

	<form name="settings" method="post" action="settings">
	    
	    <div class="form-group row">
	    	<label class="col-4">Email</label>
	    	<div class="col-6">
	    		<input type="email" required="" class="form-control" name="email" value="<?= App::get()->me->email ?>">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Имя</label>
	    	<div class="col-6">
	    		<input type="text" required=""  class="form-control" name="fullname" value="<?= App::get()->me->fullname ?>">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Телефон</label>
	    	<div class="col-6">
	    		<input type="text" name="phone"  class="form-control" value="<?= App::get()->me->phone ?>">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Город</label>
	    	<div class="col-6">
	    		<?
			    if (count($cities)) {
			    	?>
			    	<select name="cityId" class="form-control"  >
			    		<?
			    		foreach ($cities as $key => $city) {
			    			?>
			    			<option <?= App::get()->me->cityId == $city->id ? "selected" : "" ?> value="<?= $city->id ?>"><?= $city->title ?></option>
			    			<?
			    		}
			    		?>
			    	</select>
			    	<?
			    } else {
			    	echo 'Нет городов на выбор';
			    }
			    ?>
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Получать рассылку на почту</label>
	    	<?
	    	$s = new subscriber();

	    	$sub = 0;

	    	if ($s->getByEmail(App::get()->me->email)) {
	    		if (!$s->unsubscribe) {
	    			$sub = 1;
	    		}
	    	}
	    	?>
	    	<div class="col-6">
	    		<div class="checkbox">
	    			<label>
	    				<input type="checkbox" <?= $sub ? 'checked': ''  ?> name="subscribe" value="1"> Да
	    			</label>
	    		</div>
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Пароль</label>
	    	<div class="col-6">
	    		<input type="password" name="password" class="form-control">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Новый пароль</label>
	    	<div class="col-6">
	    		<input type="password" name="newpassword" class="form-control">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4">Подтвердите новый пароль</label>
	    	<div class="col-6">
	    		<input type="password" name="repassword" class="form-control">
	    	</div>
	    </div>

	    <div class="form-group row">
	    	<label class="col-4"></label>
	    	<div class="col-6">
	    		<button type="submit" class="btn btn-primary">Изменить</button>
	    	</div>
	    </div>    
	</form>

</div>