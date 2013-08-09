<?php
class model_user{

	/*
	* Создает пользователя
	*/
	public static function create_user(data_user $user){
		$user->verify('login');
		$chk_login = new data_user();
		$chk_login->login = $user->login;
		if(count(self::get_users($chk_login)) !== 0)
			throw new e_model('Пользователь с таким логином уже существует.');
		$user->id = self::get_insert_id();
		$user->verify('id', 'firstname', 'lastname', 'middlename', 'login', 
						'password', 'status');
		$sql = new sql();
		$sql->query("INSERT INTO `users` (`id`, `company_id`, `status`, `username`,
				`firstname`, `lastname`, `midlename`, `password`, `telephone`, `cellphone`)
				VALUES (:user_id, :company_id, :status, :login, :firstname, :lastname, 
				:middlename, :password, :telephone, :cellphone)");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', 2, PDO::PARAM_INT);
		$sql->bind(':status', $user->status, PDO::PARAM_STR);
		$sql->bind(':login', $user->login, PDO::PARAM_STR);
		$sql->bind(':firstname', $user->firstname, PDO::PARAM_STR);
		$sql->bind(':lastname', $user->lastname, PDO::PARAM_STR);
		$sql->bind(':middlename', $user->middlename, PDO::PARAM_STR);
		$sql->bind(':password', self::get_password_hash($user->password), PDO::PARAM_STR);
		$sql->bind(':telephone', $user->telephone, PDO::PARAM_STR);
		$sql->bind(':cellphone', $user->cellphone, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании пользователя.');
		return $user;
	}

	/**
	* Возвращает следующий для вставки user_id.
	* @return int
	*/
	private static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_user_id` FROM `users`");
		$sql->execute('Проблема при опредении следующего user_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего user_id.');
		$user_id = (int) $sql->row()['max_user_id'] + 1;
		$sql->close();
		return $user_id;
	}
	
	/**
	* Формирует хэш с солью
	* @return string
	*/
	public static function get_password_hash($password){
		return md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
	}

	/**
	* Возвращает пользователей
	* @return array из data_user
	*/
	public static function get_users(data_user $user){
		$sql = new sql();
		$sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
				`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
				`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
				`users`.`cellphone` FROM `users`");
		if(!empty($user->id)){
			$user->verify('id');
			$sql->query(" WHERE `id` = :id");
			$sql->bind(':id', $user->id, PDO::PARAM_INT);
		}
		if(!empty($user->login)){
			$user->verify('login');
			$sql->query(" WHERE `username` = :login");
			$sql->bind(':login', $user->login, PDO::PARAM_STR);
		}
		$sql->query(" ORDER BY `users`.`lastname`");
		return $sql->map(new data_user(), 'Проблема при выборке пользователей.');
	}

	/**
	* Обновляет ФИО пользователя
	* @return array из data_user
	*/
	public static function update_fio(data_user $user, $lastname, $firstname, $middlename){
		$user = new mapper_user->find($user->id);
		$user->lastname = $lastname;
		$user->firstname = $firstname;
		$user->middlename = $middlename;
		$user->verify('id', 'lastname', 'firstname', 'middlename');
		$sql = new sql();
		$sql->query("UPDATE `users` SET `lastname` = :lastname, `firstname` = :firstname,
				 	`midlename` = :middlename WHERE `id` = :id");
		$sql->bind(':id', $user->id, PDO::PARAM_INT);
		$sql->bind(':lastname', $user->lastname, PDO::PARAM_STR);
		$sql->bind(':firstname', $user->firstname, PDO::PARAM_STR);
		$sql->bind(':middlename', $user->middlename, PDO::PARAM_STR);
		$sql->execute('Проблема при обновлении ФИО пользоваля.');
		return $user;
	}

	/**
	* Обновляет пароль пользователя
	* @return object data_user
	*/
	public static function update_password(data_user $user, $password, $confirm){
		if($password !== $confirm)
			throw new e_model('Пароль и подтверждение не идентичны.');
		if(!preg_match('/^[a-zA-Z0-9]{8,}$/u', $password))
            throw new e_model('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
		$user = (new mapper_user)->find($user->id);
		$sql = new sql();
		$sql->query("UPDATE `users` SET `password` = :password WHERE `id` = :id");
		$sql->bind(':id', $user->id, PDO::PARAM_INT);
		$sql->bind(':password', model_user::get_password_hash($password), PDO::PARAM_STR);
		$sql->execute('Проблема при изменении пароля пользоваля.');
		return $user;
	}

	/**
	* Обновляет логин пользователя
	* @return object data_user
	*/
	public static function update_login(data_user $user, $login){
		$user = (new mapper_user)->find($user->id);
		$user->login = $login;
		$user->verify('id', 'login');
		// проверка на существование идентичного логина
		$sql = new sql();
		$sql->query("SELECT `id` FROM `users` WHERE `username` = :login");
		$sql->bind(':login', $user->login, PDO::PARAM_STR);
		$sql->execute("Ошибка при поиске идентичного логина.");
		if($sql->count() !== 0)
			throw new e_model('Такой логин уже существует.');
		// обвноление логина пользователя в базе данных
		$sql = new sql();
		$sql->query("UPDATE `users` SET `username` = :login WHERE `id` = :id");
		$sql->bind(':id', $user->id, PDO::PARAM_INT);
		$sql->bind(':login', $user->login, PDO::PARAM_STR);
		$sql->execute('Проблема при изменении логина пользоваля.');
		return $user;
	}

	/**
	* Верификация типа объекта пользователя.
	*/
	public static function is_data_user($user){
		if(!($user instanceof data_user))
			throw new e_model('Возвращен объект не является пользователем');
	}

	/**
	* Обновляет статус пользователя
	* @return bolean
	*/
	public static function update_user_status(data_user $user){
		$user = (new mapper_user)->find($user->id);
		if($user->status === 'true')
			$user->status = 'false';
		else
			$user->status = 'true';
		$user->verify('id', 'status');
		$sql = new sql();
		$sql->query("UPDATE `users` SET `status` = :status WHERE `id` = :id");
		$sql->bind(':status', $user->status, PDO::PARAM_STR);
		$sql->bind(':id', $user->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при изменении статуса пользователя.');
		return $user;
	}
}