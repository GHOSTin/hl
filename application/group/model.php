<?php
class model_group{
	/**
	* Возвращает список групп.
	* @return array из data_group
	*/
	public static function get_groups(data_group $group_params, data_current_user $current_user){
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `status`, `name`
					FROM `groups` WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if(!empty($group_params->id)){
			$sql->query(" AND `id` = :id");
			$sql->bind(':id', $group_params->id, PDO::PARAM_INT);
		}
		$sql->query(" ORDER BY `name`");
		return $sql->map(new data_group(), 'Проблема при выборке групп пользователей.');
	}
	/**
	* Возвращает список пользователей группы
	* @return array из data_user
	*/
	public static function get_users(data_group $group_params, data_current_user $current_user){
		self::verify_id($group_params);
		$sql = new sql();
		$sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
					`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`, `group2user` WHERE `group2user`.`group_id` = :group_id
					AND `users`.`id` = `group2user`.`user_id`");
		$sql->bind(':group_id', $group_params->id, PDO::PARAM_INT);
		return $sql->map(new data_user(), 'Проблема при выборки пользователей группы.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_group $group){
		if($group->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора группы.
	*/
	public static function verify_id(data_group $group){
		if($group->id < 1)
			throw new e_model('Идентификатор группы задан не верно.');
	}
	/**
	* Верификация названия группы.
	*/
	public static function verify_name(data_group $group){
		if(empty($group->name))
			throw new e_model('Название группы задано не верно.');
	}
	/**
	* Верификация статуса группы.
	*/
	public static function verify_status(data_group $group){
		if(empty($group->status))
			throw new e_model('Статус группы задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_group.
	*/
	public static function is_data_group($group){
		if(!($group instanceof data_group))
			throw new e_model('Возвращеный объект не является группой.');
	}
}