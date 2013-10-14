<?php
class model_profile{

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