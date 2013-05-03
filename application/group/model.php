<?php
class model_group{
	/**
	* Возвращает список групп.
	* @return array из data_group
	*/
	public static function get_groups(data_group $group_params, data_user $current_user){
		$sql = "SELECT `id`, `company_id`, `status`, `name`
				FROM `groups` WHERE `company_id` = :company_id";
		if($group_params->id > 0)
			$sql .= " AND `id` = :id";
		$sql .= " ORDER BY `name`";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if($group_params->id > 0)
			$stm->bindValue(':id', $group_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборке групп пользователей.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_group');
		$result = [];
		while($group = $stm->fetch())
			$result[] = $group;
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает список пользователей группы
	* @return array из data_user
	*/
	public static function get_users(data_group $group_params, data_user $current_user){
		self::verify_group_id($group_params);
		$sql = "SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
				`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
				`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
				`users`.`cellphone`
				FROM `users`, `group2user` WHERE `group2user`.`group_id` = :group_id
				AND `users`.`id` = `group2user`.`user_id`";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':group_id', $group_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборки пользователей группы.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_user');
		$result = [];
		while($works = $stm->fetch())
			$result[] = $works;
		$stm->closeCursor();
		return $result;
	}
	/**
	* Верификация идентификатора компании группы
	*/
	public static function verify_company_id(data_group $group){
		if($group->company_id < 1)
			throw new e_model('Идентификатор компании группы задан не верно.');
	}
	/**
	* Верификация идентификатора группы
	*/
	public static function verify_id(data_group $group){
		if($group->id < 1)
			throw new e_model('Идентификатор группы задан не верно.');
	}
	/**
	* Верификация названия группы
	*/
	public static function verify_name(data_group $group){
		if(empty($group->name))
			throw new e_model('Название группы задано не верно.');
	}
	/**
	* Верификация статуса группы
	*/
	public static function verify_status(data_group $group){
		if(empty($group->status))
			throw new e_model('Статус группы задан не верно.');
	}
	/**
	* Верификация типа объекта группы
	*/
	public static function is_data_group($group){
		if(!($group instanceof data_group))
			throw new e_model('Возвращеный объект не является группой.');
	}
}