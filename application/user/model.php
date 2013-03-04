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
			$user_id = self::get_insert_id();
			if($user_id === false)
				return false;
				$user->id = $user_id;
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
			$stm->bindValue(':status', true);
			$stm->bindValue(':login', $user->login, PDO::PARAM_STR);
			$stm->bindValue(':firstname', $user->firstname, PDO::PARAM_STR);
			$stm->bindValue(':lastname', $user->lastname, PDO::PARAM_STR);
			$stm->bindValue(':middlename', $user->middlename, PDO::PARAM_STR);
			$stm->bindValue(':password', get_password_hash($user->password), PDO::PARAM_STR);
			$stm->bindValue(':telephone', $user->telephone, PDO::PARAM_STR);
			$stm->bindValue(':cellphone', $user->cellphone, PDO::PARAM_STR);
			if($stm->execute() === false)
				return false;
				return $user;				
			$stm->closeCursor();
		}catch(exception $e){
			throw new exception('Проблемы при создании пользователя.');
		}
	}
	private static function get_insert_id(){
		try{
			$sql = "SELECT MAX(`id`) as `max_user_id` FROM `users`";
			$stm = db::get_handler()->query($sql);
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_user_id'] + 1;
				}else
					return false;
			}
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
				throw new exception('Wrong parametrs');
			$sql = "SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
					`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`
					WHERE `users`.`id` = :user_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_user');
			$user = $stm->fetch();
			$stm->closeCursor();
			return $user;
		}catch(exception $e){
			throw new exception('Fail user');
		}
	}
	/**
	* Возвращает информацию о пользователе
	* @return false or data_user
	*/
	public static function get_users($args){
		try{
			$result = [];
			$sql = "SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
					`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`";
			$stm = db::get_handler()->prepare($sql);
			$stm->execute();
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_user');
			while($user = $stm->fetch())
				$result[] = $user;
			$stm->closeCursor();
			return $result;
		}catch(exception $e){
			throw new exception('Fail users');
		}
	}	
}