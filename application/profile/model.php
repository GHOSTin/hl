<?php
class model_profile{
	/**
	* Записывает в сессию правила, ограничения, настройки, меню.
	*/
	public static function get_user_profiles(data_user $user){
		model_user::verify_id($user);
		model_user::verify_company_id($user);
		$sql = new sql();
		$sql->query("SELECT `profile`, `rules`, `restrictions`, `settings`
					FROM `profiles` WHERE  `user_id` = :user_id AND `company_id` = :company_id");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $user->company_id , PDO::PARAM_INT);
		$sql->execute('Ошибка при получении профиля.');
		if($sql->count() > 0)
			while($profile = $sql->row())
				if(array_search($profile['profile'], ['query', 'number']) !== false){
					$rules[$profile['profile']] = json_decode($profile['rules']);
					$restrictions[$profile['profile']] = json_decode($profile['restrictions']);
				}
		$sql->close();
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
		$sql = new sql();
		$sql->query("UPDATE `users` SET `password` = :password WHERE `id` = :id");
		$sql->bind(':password', $user->password, PDO::PARAM_STR);
		$sql->bind(':id', $user->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при изменении пароля.');
		return $user;
	}
	/**
	* Обновляет номер сотового телефона пользователя
	* @return bolean
	*/
	public static function update_cellphone(data_user $user, $cellphone){
		$user->cellphone = (string) $cellphone;
		$sql = new sql();
		$sql->query("UPDATE `users` SET `cellphone` = :cellphone WHERE `id` = :id");
		$sql->bind(':cellphone', $user->cellphone, PDO::PARAM_STR);
		$sql->bind(':id', $user->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при изменении номера сотового телефона.');
		return $user;
	}
	/**
	* Обновляет номер телефона пользователя
	* @return bolean
	*/
	public static function update_telephone(data_user $user, $telephone){
		$user->telephone = (string) $telephone;
		$sql = new sql();
		$sql->query("UPDATE `users` SET `telephone` = :telephone WHERE `id` = :id");
		$sql->bind(':telephone', $user->telephone, PDO::PARAM_STR);
		$sql->bind(':id', $user->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при изменении номера телефона.');
		return $user;
	}
}