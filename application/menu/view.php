<?php
class view_menu{
	public static function build_horizontal_menu(){
		$args['user_name'] = $_SESSION['user']->firstname.' '.$_SESSION['user']->lastname;
        $args['menu'] = [['href' => 'task', 'text' => 'Задачи'],
					       	['href' => 'user', 'text' => 'Администрирование пользователей'],
					       	['href' => 'query', 'text' => 'Заявки'],
					        ['href' => 'number','text' => 'Жилой фонд'],
					        ['href' => 'company','text' => 'Компания']];
		return load_template('menu.build_horizontal_menu', $args);
	}
}