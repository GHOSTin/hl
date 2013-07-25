<?php
class model_profile{
	/**
	* Записывает в сессию правила, ограничения, настройки, меню.
	*/
	public static function get_user_profiles(data_company $company, data_current_user $user){
		// var_dump();
		model_user::verify_id($user);
		model_company::verify_id($company);
		$sql = new sql();
		$sql->query("SELECT `profile`, `rules`, `restrictions`, `settings`
					FROM `profiles` WHERE  `user_id` = :user_id AND `company_id` = :company_id");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
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
	* Возвращает список компаний для которых есть профиль пользователя.
	*/
	public static function get_companies(data_user $user){
		$user->verify('id');
		$sql = new sql();
		$sql->query("SELECT DISTINCT `companies`.`id`, `companies`.`name`
					FROM `companies`, `profiles` WHERE `profiles`.`user_id` = :user_id
					AND `profiles`.`company_id` = `companies`.`id`");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		return $sql->map(new data_company(), 'Проблемы при получении компаний в профиле.');
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
	public static function update_password(data_user $user, $password){
		$password = (string) $password;
		if(strlen($password) < 6)
			throw new e_model('Пароль не может быть длиной меньше 10 символов.');
		$user->password = model_user::get_password_hash($password);
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