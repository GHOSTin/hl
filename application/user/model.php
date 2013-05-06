<?php
class model_user{
	/*
	* Создает пользователя
	*/
	public static function create_user(data_user $user, data_current_user $current_user){
		self::verify_user_login($user);
		self::verify_user_password($user);
		self::verify_user_firstname($user);
		self::verify_user_lastname($user);
		self::verify_user_company_id($current_user);
		$user->company_id = $current_user->company_id;
		$user->id = self::get_insert_id();
		$user->status = true;
		$sql = new sql();
		$sql->exp("INSERT INTO `users` (`id`, `company_id`, `status`, `username`,
				`firstname`, `lastname`, `midlename`, `password`, `telephone`, `cellphone`
				) VALUES (:user_id, :company_id, :status, :login, :firstname, :lastname, 
				:middlename, :password, :telephone, :cellphone)");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $user->company_id, PDO::PARAM_INT);
		$sql->bind(':status', $user->status);
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
		$sql->close_cursor();
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
	public static function get_users(data_user $user_params){
		$sql = new sql();
		$sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
				`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
				`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
				`users`.`cellphone` FROM `users`");
		if(!empty($user_params->id)){
			$sql->query(" WHERE `id` = :id");
			$sql->bind(':id', $user_params->id, PDO::PARAM_INT);
		}
		return $sql->result(new data_user(), 'Проблема при выборке пользователей.');
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