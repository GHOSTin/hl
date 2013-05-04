<?php
class model_profile{
	/**
	* Записывает в сессию правила, ограничения, настройки, меню.
	*/
	public static function get_user_profiles(data_user $user){
		model_user::verify_id($user);
		model_user::verify_company_id($user);
		$sql = "SELECT `profile`, `rules`, `restrictions`, `settings`
				FROM `profiles` WHERE  `user_id` = :user_id AND `company_id` = :company_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':user_id', $user->id, PDO::PARAM_INT);
		$stm->bindValue(':company_id', $user->company_id , PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при получении профиля.');
		if($stm->rowCount() > 0){
			while($profile = $stm->fetch()){
				if(array_search($profile['profile'], ['query', 'number']) !== false){
					$rules[$profile['profile']] = json_decode($profile['rules']);
					$restrictions[$profile['profile']] = json_decode($profile['restrictions']);
				}
			}
		}
		$stm->closeCursor();
		model_session::set_rules($rules);
		model_session::set_restrictions($restrictions);
	}
	/**
	* Проверяет права доступа пользователя.
	* @return bolean
	*/
	public static function check_general_access($controller, $component){
		if(property_exists($controller, 'rules')){
			if($_SESSION['rules'][$component]->generalAccess !== true)
				return false;
			else
				return true;
		}else
			return true;
	}
	/**
	* Обновляет пароль пользователя
	* @return bolean
	*/
	public static function update_password(data_user $user, $new_password){
		$new_password = (string) $new_password;
		if(strlen($new_password) < 6)
			throw new e_model('Пароль не может быть длиной меньше 10 символов.');
		$user->password = model_user::get_password_hash($new_password);
		$sql = 'UPDATE `users` SET `password` = :password
				WHERE `id` = :id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':password', $user->password, PDO::PARAM_STR);
		$stm->bindValue(':id', $user->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при изменении пароля.');
		return $user;
	}
	/**
	* Обновляет номер сотового телефона пользователя
	* @return bolean
	*/
	public static function update_cellphone(data_user $user, $cellphone){
		$user->cellphone = (string) $cellphone;
		$sql = 'UPDATE `users` SET `cellphone` = :cellphone
				WHERE `id` = :id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':cellphone', $user->cellphone, PDO::PARAM_STR);
		$stm->bindValue(':id', $user->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при изменении номера сотового телефона.');
		return $user;
	}
	/**
	* Обновляет номер телефона пользователя
	* @return bolean
	*/
	public static function update_telephone(data_user $user, $telephone){
		$user->telephone = (string) $telephone;
		$sql = 'UPDATE `users` SET `telephone` = :telephone
				WHERE `id` = :id';
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':telephone', $user->telephone, PDO::PARAM_STR);
		$stm->bindValue(':id', $user->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Ошибка при изменении номера телефона.');
		return $user;
	}
}