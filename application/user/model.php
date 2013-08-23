<?php
class model_user{

	/*
	* Создает пользователя
	*/
	public function create_user(data_user $user){
		$mapper = new mapper_user();
		return $mapper->insert($user);
	}

	/**
	* Формирует хэш с солью
	* @return string
	*/
	public function get_password_hash($password){
		return md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
	}

	/**
	* Возвращает объект пользователя по идентификатору
	*/
	public function get_user($id){
		return (new mapper_user)->find($id);
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
	public function update_fio($id, $lastname, $firstname, $middlename){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->lastname = $lastname;
		$user->firstname = $firstname;
		$user->middlename = $middlename;
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет пароль пользователя
	* @return object data_user
	*/
	public function update_password($id, $password){
		if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $password))
            throw new e_model('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 символов.');
        $mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->password = $this->get_password_hash($password);
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет логин пользователя
	* @return object data_user
	*/
	public function update_login($id, $login){
		// проверка на существование идентичного логина
		$sql = new sql();
		$sql->query("SELECT `id` FROM `users` WHERE `username` = :login");
		$sql->bind(':login', $login, PDO::PARAM_STR);
		$sql->execute("Ошибка при поиске идентичного логина.");
		if($sql->count() !== 0)
			throw new e_model('Такой логин уже существует.');
		// обвноление логина пользователя в базе данных
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->login = $login;
		$mapper->update($user);
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
	*/
	public function update_user_status($id){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		if($user->status === 'true')
			$user->status = 'false';
		else
			$user->status = 'true';
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет номер сотового телефона пользователя
	*/
	public static function update_cellphone($id, $cellphone){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->cellphone = $cellphone;
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет номер телефона пользователя
	*/
	public function update_telephone($id, $telephone){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->telephone = $telephone;
		$mapper->update($user);
		return $user;
	}
}