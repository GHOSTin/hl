<?php
class model_user{
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
					`users`.`username`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`
					WHERE `users`.`id` = :user_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stm->execute();
			$record = $stm->fetch();
			$stm->closeCursor();
			return self::build_user_object($record);
		}catch(exception $e){
			return false;
		}
	}
	/**
	* Строит объект пользователя
	* @param array $record
	* @return object data_user
	*/
	public static function build_user_object($record){
		$user = new data_user();
		$user->id = $record['id'];
		$user->company_id = $record['company_id'];
		$user->status = $record['status'];
		$user->login = $record['username'];
		$user->firstname = $record['firstname'];
		$user->lastname = $record['lastname'];
		$user->middlename = $record['midlename'];
		$user->telephone = $record['telephone'];
		$user->cellphone = $record['cellphone'];
		return $user;
	}
}