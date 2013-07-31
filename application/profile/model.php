<?php
class model_profile{

	/**
	* Добавляет профиль.
	*/
	public static function add_profile(data_company $company, data_user $user, $profile){
		$user->verify('id');
		model_company::verify_id($company);
		$time 		= getdate();
		$beginDay 	= mktime(0,0,0, $time['mon'],$time['mday'],$time['year']);
		$endDay 	= $beginDay + 86400;

		# import
		$profiles['import']['rules']['generalAccess'] = false;
		
		# materialgroup
		$profiles['materialgroup']['rules']['generalAccess'] = false;
		$profiles['materialgroup']['rules']['createMaterial'] = false;
		$profiles['materialgroup']['rules']['createMaterialgroup'] = false;
		$profiles['materialgroup']['rules']['deleteMaterial'] = false;
		$profiles['materialgroup']['rules']['deleteMaterialgroup'] = false;
		$profiles['materialgroup']['rules']['editMaterial'] = false;
		$profiles['materialgroup']['rules']['editMaterialgroup'] = false;
		
		# meter
		$profiles['meter']['rules']['generalAccess'] = false;
		
		# number
		$profiles['number']['rules']['generalAccess'] = false;
		$profiles['number']['rules']['createDepartment'] = false;
		$profiles['number']['rules']['createNumber'] = false;
		$profiles['number']['rules']['editMeter'] = false;
		$profiles['number']['rules']['editNumber'] = false;
		$profiles['number']['rules']['editNumberContact'] = false;
		$profiles['number']['rules']['sendSms'] = false;
		
		$profiles['number']['settings']['report'] = array();
		$profiles['number']['settings']['status'] = array();
		$profiles['number']['settings']['streets'] = array();
		$profiles['number']['settings']['houses'] = array();
		$profiles['number']['settings']['departments'] = array();
		$profiles['number']['settings']['worktypes'] = array();
		$profiles['number']['settings']['time'] = array('begin'=> $beginDay, 'end' => $endDay);
		
		# phrase
		$profiles['phrase']['rules']['generalAccess'] = false;
		$profiles['phrase']['rules']['createPhrase'] = false;
		
		# report
		$profiles['report']['rules']['generalAccess'] = false;
		
		$profiles['report']['settings']['report'] = array();
		$profiles['report']['settings']['status'] = array();
		$profiles['report']['settings']['streets'] = array();
		$profiles['report']['settings']['houses'] = array();
		$profiles['report']['settings']['departments'] = array();
		$profiles['report']['settings']['initiators'] = array();
		$profiles['report']['settings']['worktypes'] = array();
		$profiles['report']['settings']['users'] = array();
		$profiles['report']['settings']['time'] = array('begin'=> $beginDay, 'end' => $endDay);				
		
		# query
		$profiles['query']['rules']['generalAccess'] = false;
		$profiles['query']['rules']['createQuery'] = false;
		$profiles['query']['rules']['closeQuery'] = false;
		$profiles['query']['rules']['editContact'] = false;
		$profiles['query']['rules']['editDescriptionOpen'] = false;
		$profiles['query']['rules']['editDescriptionClose'] = false;
		$profiles['query']['rules']['editInitiator'] = false;
		$profiles['query']['rules']['editPaymentStatus'] = false;
		$profiles['query']['rules']['editPerformers'] = false;
		$profiles['query']['rules']['editManagers'] = false;
		$profiles['query']['rules']['editMaterials'] = false;
		$profiles['query']['rules']['editNumbers'] = false;
		$profiles['query']['rules']['editWarningType'] = false;
		$profiles['query']['rules']['editWorks'] = false;
		$profiles['query']['rules']['editWorkType'] = false;
		$profiles['query']['rules']['editInspection'] = false;
		$profiles['query']['rules']['reopenQuery'] = false;
		$profiles['query']['rules']['sendSms'] = false;
		$profiles['query']['rules']['showHistory'] = false;
		$profiles['query']['rules']['showDocs'] = false;
		
		$profiles['query']['restrictions']['departments'] = array();
		$profiles['query']['restrictions']['worktypes'] = array();
		
		$profiles['query']['settings']['status'] = array();
		$profiles['query']['settings']['streets'] = array();
		$profiles['query']['settings']['houses'] = array();
		$profiles['query']['settings']['departments'] = array();
		$profiles['query']['settings']['initiators'] = array();
		$profiles['query']['settings']['worktypes'] = array();
		$profiles['query']['settings']['users'] = array();
		$profiles['query']['settings']['time'] = array('begin'=> $beginDay, 'end' => $endDay);		
		
		# system
		$profiles['system']['rules']['generalAccess'] = false;
		
		# user
		$profiles['user']['rules']['generalAccess'] = false;
		$profiles['user']['rules']['addGroup'] = false;
		$profiles['user']['rules']['addUser'] = false;
		$profiles['user']['rules']['createGroup'] = false;
		$profiles['user']['rules']['createUser'] = false;
		$profiles['user']['rules']['deleteGroup'] = false;
		$profiles['user']['rules']['deleteUser'] = false;
		$profiles['user']['rules']['editGroup'] = false;
		$profiles['user']['rules']['editUser'] = false;
		$profiles['user']['rules']['setPassword'] = false;
		$profiles['user']['rules']['setRule'] = false;
		
		# workgroup
		$profiles['workgroup']['rules']['generalAccess'] = false;
		$profiles['workgroup']['rules']['createWork'] = false;
		$profiles['workgroup']['rules']['createWorkgroup'] = false;
		$profiles['workgroup']['rules']['deleteWork'] = false;
		$profiles['workgroup']['rules']['deleteWorkgroup'] = false;
		$profiles['workgroup']['rules']['editWork'] = false;
		$profiles['workgroup']['rules']['editWorkgroup'] = false;

		if(!array_key_exists($profile, $profiles))
			throw new e_model('Не существует такого профиля.');

		$sql = new sql();
		$sql->query("SELECT `rules`, `restrictions`, `settings` FROM `profiles` 
					WHERE  `user_id` = :user_id AND `company_id` = :company_id
					AND `profile` = :profile");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
		$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
		$sql->execute('Ошибка при получении профиля.');
		if($sql->count() > 0)
			throw new e_model('Профиль уже существует');
		$sql = new sql();
		$sql->query("INSERT INTO `profiles` (`company_id`, `user_id`, `profile`,
					`rules`, `restrictions`, `settings`) VALUES (:company_id, :user_id,
					:profile, :rules, :restrictions, :settings)");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
		$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
		$sql->bind(':rules', json_encode($profiles[$profile]['rules']), PDO::PARAM_STR);
		$sql->bind(':restrictions', json_encode($profiles[$profile]['restrictions']), PDO::PARAM_STR);
		$sql->bind(':settings', json_encode($profiles[$profile]['settings']), PDO::PARAM_STR);
		$sql->execute('Ошибка при добавлении профиля.');
	}

	/**
	* Удаляет профиль.
	*/
	public static function delete_profile(data_company $company, data_user $user, $profile){
		$user->verify('id');
		model_company::verify_id($company);
		$sql = new sql();
		$sql->query("DELETE FROM `profiles` WHERE  `user_id` = :user_id
					AND `company_id` = :company_id AND `profile` = :profile");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
		$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
		$sql->execute('Ошибка при удалении профиля.');
	}

	/**
	* Записывает в сессию правила, ограничения, настройки, меню.
	*/
	public static function get_user_profiles(data_company $company, data_current_user $user){
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
	* Возвращает название профилей пользователя в зависимости от компании.
	*/
	public static function get_profiles(data_company $company, data_user $user){
		$user->verify('id');
		model_company::verify_id($company);
		$sql = new sql();
		$sql->query("SELECT `profile` FROM `profiles` 
					WHERE  `user_id` = :user_id AND `company_id` = :company_id");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
		$sql->execute('Ошибка при получении профиля.');
		$profiles = [];
		if($sql->count() > 0)
			while($profile = $sql->row())
				$profiles[] = $profile['profile'];
		$sql->close();
		return $profiles;
	}

	/**
	* Возвращает профиль пользователя в зависимости от компании и названия профиля.
	*/
	public static function get_profile(data_company $company, data_user $user, $profile){
		$user->verify('id');
		model_company::verify_id($company);
		$sql = new sql();
		$sql->query("SELECT `rules`, `restrictions`, `settings` FROM `profiles` 
					WHERE  `user_id` = :user_id AND `company_id` = :company_id
					AND `profile` = :profile");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
		$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
		$sql->execute('Ошибка при получении профиля.');
		if($sql->count() === 0)
			throw new e_model('Профиля не существует.');
		if($sql->count() !== 1)
			throw new e_model('Неверное количество профилей.');			
		$row = $sql->row();
		$profile = [];
		$profile['rules'] = (array) json_decode($row['rules']);
		$profile['restrictions'] = (array) json_decode($row['restrictions']);
		$sql->close();
		return $profile;
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
	* Возвращает профиль пользователя в зависимости от компании и названия профиля.
	*/
	public static function update_rule(data_company $company, data_user $user, $profile, $rule){
		$user->verify('id');
		model_company::verify_id($company);
		$rules = self::get_profile($company, $user, $profile)['rules'];
		if(in_array($rule, array_keys($rules))){
			$rules[$rule] = !$rules[$rule];
			$sql = new sql();
			$sql->query('UPDATE `profiles` SET `rules` = :rules WHERE `company_id` = :company_id
				AND `user_id` = :user_id AND `profile` = :profile');
			$sql->bind(':rules', (string) json_encode($rules), PDO::PARAM_STR);
			$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
			$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
			$sql->execute('Проблема при обновлении правила.');
		}else
			throw new e_model('Правила '.$rule.' нет в профиле '.$profile);
		return $rules[$rule];
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