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
						'password', 'status', 'telephone', 'cellphone');
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
		$user->verify('id');
		$users = self::get_users($user);
		if(count($users) !== 1)
			throw new e_model('Ожидаемое количество пользователей не верно.');
		$user = $users[0];
		self::is_data_user($user);
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
		if(strlen($password) < 8)
			throw new e_model('Пароль не удовлетворяет усливиям безопасности');
		if(!preg_match('/^[а-яА-Яa-zA-Z0-9]+$/u', $password))
            throw new e_model('Пароль не удовлетворяет а-яА-Яa-zA-Z0-9.');
		$user->verify('id');
		$users = self::get_users($user);
		if(count($users) !== 1)
			throw new e_model('Ожидаемое количество пользователей не верно.');
		$user = $users[0];
		self::is_data_user($user);
		$user->verify('id');
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
		$user->verify('id');
		// проверка существования обновляемого пользователя
		$users = self::get_users($user);
		if(count($users) !== 1)
			throw new e_model('Ожидаемое количество пользователей не верно.');
		$user = $users[0];
		self::is_data_user($user);
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
	* Верификация сотового телефона пользователя.
	*/
	public static function verify_cellphone(data_user $user){
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_user $user){
		if($user->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация имени пользователя.
	*/
	public static function verify_firstname(data_user $user){
		if(empty($user->firstname))
			throw new e_model('Имя пользователя задано не верно.');
	}
	/**
	* Верификация идентификатора пользователя.
	*/
	public static function verify_id(data_user $user){
		if($user->id < 1)
			throw new e_model('Идентификатор пользователя задан не верно.');
	}
	/**
	* Верификация фамилии пользователя.
	*/
	public static function verify_lastname(data_user $user){
		if(empty($user->lastname))
			throw new e_model('Фамилия пользователя задана не верно.');
	}
	/**
	* Верификация логина пользователя.
	*/
	public static function verify_login(data_user $user){
		if(empty($user->login))
			throw new e_model('Логин пользователя задан не верно.');
	}
	/**
	* Верификация отчества пользователя.
	*/
	public static function verify_middlename(data_user $user){
	}
	/**
	* Верификация пароля пользователя.
	*/
	public static function verify_password(data_user $user){
		if(empty($user->password))
			throw new e_model('Пароля пользователя задан не верно.');
	}
	/**
	* Верификация пароля пользователя.
	*/
	public static function verify_session(data_user $user){
	}
	/**
	* Верификация статуса пользователя.
	*/
	public static function verify_status(data_user $user){
	}
	/**
	* Верификация телефона пользователя.
	*/
	public static function verify_telephone(data_user $user){
	}
	/**
	* Верификация типа объекта пользователя.
	*/
	public static function is_data_user($user){
		if(!($user instanceof data_user))
			throw new e_model('Возвращен объект не является пользователем');
	}
}