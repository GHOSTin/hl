<?php
class model_group{

	/**
	* Добавляет в группу нового пользователя.
	*/
	public static function add_user(data_company $company, data_group $group, data_user $user){
		$user->verify('id');
		$users = model_user::get_users($user);
		if(count($users) !== 1)
			throw new e_model('Неверное число пользователей.');
		$user = $users[0];
		model_user::is_data_user($user);
		model_company::verify_id($company);
		$group->verify('id');
		$groups = model_group::get_groups($company, $group);
		if(count($groups) !== 1)
			throw new e_model('Неверное число пользователей');
		$group = $groups[0];
		self::is_data_group($group);
		$sql = new sql();
		$sql->query('SELECT * FROM `group2user` WHERE `group_id` = :group_id
					AND `user_id` = :user_id');
		$sql->bind(':group_id', $group->id, PDO::PARAM_INT);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при проверке дубликата связи.');
		if($sql->count() > 0)
			throw new e_model('Такой пользователь уже существует.');
		$sql = new sql();
		$sql->query("INSERT INTO `group2user` (`group_id`, `user_id`)
					VALUES (:group_id, :user_id)");
		$sql->bind(':group_id', $group->id, PDO::PARAM_INT);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при добавлении пользователя в группу.');
	}
	/**
	* Возвращает список групп.
	* @return array из data_group
	*/
	public static function get_groups(data_company $company, data_group $group){
		model_company::verify_id($company);
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `status`, `name`
					FROM `groups` WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!empty($group->id)){
			self::verify_id($group);
			$sql->query(" AND `id` = :id");
			$sql->bind(':id', $group->id, PDO::PARAM_INT);
		}
		$sql->query(" ORDER BY `name`");
		return $sql->map(new data_group(), 'Проблема при выборке групп пользователей.');
	}
	/**
	* Возвращает список пользователей группы
	* @return array из data_user
	*/
	public static function get_users(data_company $company, data_group $group){
		self::verify_id($group);
		model_company::verify_id($company);
		$sql = new sql();
		$sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
					`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`, `group2user` WHERE `group2user`.`group_id` = :group_id
					AND `users`.`id` = `group2user`.`user_id`");
		$sql->bind(':group_id', $group->id, PDO::PARAM_INT);
		return $sql->map(new data_user(), 'Проблема при выборки пользователей группы.');
	}

	/**
	* Обновляет название группы.
	* @return object data_group
	*/
	public static function update_name(data_company $company, data_group $group, $name){
		$group->verify('id');
		model_company::verify_id($company);
		// проверка существования группы
		$groups = self::get_groups($company, $group);
		if(count($groups) !== 1)
			throw new e_model('Ожидаемое количество групп не верно.');
		$group = $groups[0];
		self::is_data_group($group);
		$group->name = $name;
		$group->verify('id', 'name');
		// обвноление названия группы
		$sql = new sql();
		$sql->query("UPDATE `groups` SET `name` = :name WHERE `company_id` = :company_id
					AND `id` = :id");
		$sql->bind(':id', $group->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':name', $group->name, PDO::PARAM_STR);
		$sql->execute('Проблема при изменении названия группы.');
		return $group;
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