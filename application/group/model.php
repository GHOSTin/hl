<?php
class model_group{

	/**
	* Создает группу.
	* @return object data_group
	*/
	public static function create_group(data_company $company, data_group $group){
		$company->verify('id');
		$group->verify('name');
		if(count(self::get_groups($company, $group)) !== 0)
			throw new e_model('Группа с таким названием уже существует.');
		$group->id = self::get_insert_id($company);
		$group->company_id = $company->id;
		$group->status = 'true';
		$group->verify('id', 'name', 'status', 'company_id');
		$sql = new sql();
		$sql->query('INSERT INTO `groups` (`id`, `company_id`, `name`, `status`)
					VALUES (:id, :company_id, :name, :status)');
		$sql->bind(':id', $group->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $group->company_id, PDO::PARAM_INT);
		$sql->bind(':name', $group->name, PDO::PARAM_STR);
		$sql->bind(':status', $group->status, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании группы.');
		return $group;
	}

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
		$company->verify('id');
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
	* Исключает из группы пользователя.
	*/
	public static function exclude_user(data_company $company, data_group $group, data_user $user){
		$sql = new sql();
		$sql->query("DELETE FROM `group2user` WHERE `group_id` = :group_id
					AND `user_id` = :user_id");
		$sql->bind(':group_id', $group->id, PDO::PARAM_INT);
		$sql->bind(':user_id', $user->id, PDO::PARAM_INT);
		$sql->execute('Ошибка при исключении пользователя из группы.');
	}

	/**
	* Возвращает следующий для вставки идентификатор группы.
	* @return int
	*/
	private static function get_insert_id(data_company $company){
	    $company->verify('id');
	    $sql = new sql();
	    $sql->query("SELECT MAX(`id`) as `max_id` FROM `groups`
	                WHERE `company_id` = :company_id");
	    $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
	    $sql->execute('Проблема при опредении следующего group_id.');
	    if($sql->count() !== 1)
	        throw new e_model('Проблема при опредении следующего group_id.');
	    $id = (int) $sql->row()['max_id'] + 1;
	    $sql->close();
	    return $id;
	}

	/**
	* Возвращает список групп.
	* @return array из data_group
	*/
	public static function get_groups(data_company $company, data_group $group){
		$company->verify('id');
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `status`, `name`
					FROM `groups` WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!empty($group->id)){
			$group->verify('id');
			$sql->query(" AND `id` = :id");
			$sql->bind(':id', $group->id, PDO::PARAM_INT);
		}
		if(!empty($group->name)){
			$group->verify('name');
			$sql->query(" AND `name` = :name");
			$sql->bind(':name', $group->name, PDO::PARAM_STR);
		}
		$sql->query(" ORDER BY `name`");
		return $sql->map(new data_group(), 'Проблема при выборке групп пользователей.');
	}
	
	/**
	* Возвращает список пользователей группы
	* @return array из data_user
	*/
	public static function get_users(data_company $company, data_group $group){
		$group->verify('id');
		$company->verify('id');
		$sql = new sql();
		$sql->query("SELECT `users`.`id`, `users`.`company_id`,`users`.`status`,
					`users`.`username` as `login`, `users`.`firstname`, `users`.`lastname`,
					`users`.`midlename` as `middlename`, `users`.`password`, `users`.`telephone`,
					`users`.`cellphone`
					FROM `users`, `group2user` WHERE `group2user`.`group_id` = :group_id
					AND `users`.`id` = `group2user`.`user_id` ORDER BY `users`.`lastname`");
		$sql->bind(':group_id', $group->id, PDO::PARAM_INT);
		return $sql->map(new data_user(), 'Проблема при выборки пользователей группы.');
	}

	/**
	* Обновляет название группы.
	* @return object data_group
	*/
	public static function update_name(data_company $company, data_group $group, $name){
		$group->verify('id');
		$company->verify('id');
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
	* Проверка принадлежности объекта к классу data_group.
	*/
	public static function is_data_group($group){
		if(!($group instanceof data_group))
			throw new e_model('Возвращеный объект не является группой.');
	}
}