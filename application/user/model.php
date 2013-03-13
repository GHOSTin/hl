<?php
class model_user{
	/*
	* Создает пользователя
	*/
	public static function create_user(data_user $user, data_user $current_user){
		try{
			if(empty($user->login) OR empty($user->firstname)
				OR empty($user->lastname) OR empty($user->password)
			) throw new exception('Не все параметры заданы правильно.');
			$user->company_id = $current_user->company_id;
			$user->id = self::get_insert_id();
			$user->status = true;
			$sql = "INSERT INTO `users` (
						`id`, `company_id`, `status`, `username`, `firstname`, `lastname`,
						`midlename`, `password`, `telephone`, `cellphone`
					) VALUES (
						:user_id, :company_id, :status, :login, :firstname, :lastname, 
						:middlename, :password, :telephone, :cellphone
					);";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':user_id', $user->id, PDO::PARAM_INT);
			$stm->bindValue(':company_id', $user->company_id, PDO::PARAM_INT);
			$stm->bindValue(':status', $user->status);
			$stm->bindValue(':login', $user->login, PDO::PARAM_STR);
			$stm->bindValue(':firstname', $user->firstname, PDO::PARAM_STR);
			$stm->bindValue(':lastname', $user->lastname, PDO::PARAM_STR);
			$stm->bindValue(':middlename', $user->middlename, PDO::PARAM_STR);
			$stm->bindValue(':password', self::get_password_hash($user->password), PDO::PARAM_STR);
			$stm->bindValue(':telephone', $user->telephone, PDO::PARAM_STR);
			$stm->bindValue(':cellphone', $user->cellphone, PDO::PARAM_STR);
			if($stm->execute() === false)
				throw new exception('Проблемы при создании пользователя.');
			$stm->closeCursor();
			return $user;
		}catch(exception $e){
			throw new exception('Проблемы при создании пользователя.');
		}
	}
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_user_id` FROM `users`";
			$stm = db::get_handler()->query($sql);
			if($stm === false){
				throw new exception('Проблема при опредении следующего user_id.');
			if($stm->rowCount() !== 1){
				throw new exception('Проблема при опредении следующего user_id.');
			$user_id = (int) $stm->fetch()['max_user_id'] + 1;
			$stm->closeCursor();
			return $user_id;
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего user_id.');
		}
	}
	public static function get_password_hash($password){
		return md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
	}
	/**
	* Возвращает информацию о пользователе
	* @return false or data_user
	*/
	public static function get_user($args){
		try{
			$user_id = $args['user_id'];
			if(empty($user_id))
				throw new exception('Не все параметры заданы правильно.');
			$sql = "SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
					`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`
					WHERE `users`.`id` = :user_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			if($stm->execute() === false)
				throw new exception('Проблема при выборке пользователя');
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_user');
			$user = $stm->fetch();
			$stm->closeCursor();
			return $user;
		}catch(exception $e){
			throw new exception('Проблема при выборке пользователя');
		}
	}
	/**
	* Возвращает информацию о пользователе
	* @return false or data_user
	*/
	public static function get_users($args){
		try{
			$sql = "SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
					`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`";
			$stm = db::get_handler()->prepare($sql);
			if($stm->execute() === false)
				throw new exception('Проблема при выборке пользователей.');
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_user');
			$result = [];
			while($user = $stm->fetch())
				$result[] = $user;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			throw new exception('Проблема при выборке пользователей.');
		}
	}	
}