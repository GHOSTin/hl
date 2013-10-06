<?php
class model_profile{

	/**
	* Добавляет профиль.
	*/
	public static function add_profile($company_id, $user_id, $profile){
		// $user->verify('id');
		// $company->verify('id');
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
		$sql->bind(':user_id', (int) $user_id, PDO::PARAM_INT);
		$sql->bind(':company_id', (int) $company_id , PDO::PARAM_INT);
		$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
		$sql->execute('Ошибка при получении профиля.');
		if($sql->count() > 0)
			throw new e_model('Профиль уже существует');
		$sql = new sql();
		$sql->query("INSERT INTO `profiles` (`company_id`, `user_id`, `profile`,
					`rules`, `restrictions`, `settings`) VALUES (:company_id, :user_id,
					:profile, :rules, :restrictions, :settings)");
		$sql->bind(':user_id', $user_id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company_id , PDO::PARAM_INT);
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
		$company->verify('id');
		$sql = new sql();
		$sql->query("DELETE FROM `profiles` WHERE  `user_id` = :user_id
					AND `company_id` = :company_id AND `profile` = :profile");
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
		$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
		$sql->execute('Ошибка при удалении профиля.');
	}

	/**
	* Возвращает список компаний для которых есть профиль пользователя.
	*/
	public static function get_companies($user_id){
		$sql = new sql();
		$sql->query("SELECT DISTINCT `companies`.`id`, `companies`.`name`
					FROM `companies`, `profiles` WHERE `profiles`.`user_id` = :user_id
					AND `profiles`.`company_id` = `companies`.`id`");
		$sql->bind(':user_id', (int) $user_id, PDO::PARAM_INT);
		return $sql->map(new data_company(), 'Проблемы при получении компаний в профиле.');
	}

	/**
	* Обновляет ограничение.
	*/
	public static function update_restriction(data_company $company, data_user $user, $profile, $restriction, $item){
		$user->verify('id');
		$company->verify('id');
		$restrictions = self::get_profile($company, $user, $profile)['restrictions'];
		if(array_key_exists($restriction, $restrictions)){
			$item = (int) $item;
			$hndl = array_search($item, $restrictions[$restriction]);
			if($hndl === false){
				$restrictions[$restriction][] = $item;
				$status = true;
			}else{
				unset($restrictions[$restriction][$hndl]);
				$status = false;
			}
			$rest = [];
			if(!empty($restrictions[$restriction]))
				foreach($restrictions[$restriction] as $value)
					$rest[] = $value;
			$restrictions[$restriction] = $rest;
			$sql = new sql();
			$sql->query('UPDATE `profiles` SET `restrictions` = :restrictions WHERE `company_id` = :company_id
				AND `user_id` = :user_id AND `profile` = :profile');
			$sql->bind(':restrictions', (string) json_encode((array) $restrictions), PDO::PARAM_STR);
			$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
			$sql->bind(':company_id', $company->id , PDO::PARAM_INT);
			$sql->bind(':profile', (string) $profile, PDO::PARAM_STR);
			$sql->execute('Проблема при обновлении правила.');
		}else
			throw new e_model('Нет ограничения '.$restriction.' в профиле '.$profile);
		return $status;
	}
}