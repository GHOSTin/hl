<?php
class model_user{

	/*
	* Создает пользователя
	*/
	public function create_user($lastname, $firstname, $middlename, $login,
		$password, $status){
		$user = new data_user();
		$user->set_lastname($lastname);
		$user->set_firstname($firstname);
		$user->set_middlename($middlename);
		$user->set_login($login);
		$user->set_hash($this->get_password_hash($password));
		$user->set_status($status);
		$mapper = new mapper_user();
		return $mapper->insert($user);
	}

	/**
	* Формирует хэш с солью
	*/
	public function get_password_hash($password){
		return md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
	}

	/**
	* Возвращает объект пользователя по идентификатору
	*/
	public function get_user($id){
		$user = (new mapper_user)->find($id);
		if(!($user instanceof data_user))
			throw new e_model('Не существует такого пользователя.');
		return $user;
	}

	/**
	* Возвращает список пользователей.
	*/
	public function get_users(){
		return (new mapper_user)->get_users();
	}

	/**
	* Обновляет ФИО пользователя
	*/
	public function update_fio($id, $lastname, $firstname, $middlename){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->set_lastname($lastname);
		$user->set_firstname($firstname);
		$user->set_middlename($middlename);
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет пароль пользователя
	* @return object data_user
	*/
	public function update_password($id, $password){
		data_user::verify_password($password);
    $mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->set_hash($this->get_password_hash($password));
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
		$user->set_login($login);
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет статус пользователя
	*/
	public function update_user_status($id){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		if($user->get_status() === 'true')
			$user->set_status('false');
		else
			$user->set_status('true');
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет номер сотового телефона пользователя
	*/
	public static function update_cellphone($id, $cellphone){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->set_cellphone($cellphone);
		$mapper->update($user);
		return $user;
	}

	/**
	* Обновляет номер телефона пользователя
	*/
	public function update_telephone($id, $telephone){
		$mapper = new mapper_user();
		$user = $mapper->find($id);
		$user->set_telephone($telephone);
		$mapper->update($user);
		return $user;
	}
}