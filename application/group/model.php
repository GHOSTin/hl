<?php
class model_group{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		$this->company->verify('id');
	}

	/**
	* Создает группу.
	* @return object data_group
	*/
	public function create_group($name, $status){
		$mapper = new mapper_group($this->company);
		if(!is_null($mapper->find_by_name($name)))
			throw new e_model('Группа с таким название уже существует.');
		$group = new data_group();
		$group->set_id($mapper->get_insert_id());
		$group->set_company_id($mapper->get_company_id());
		$group->set_name($name);
		$group->set_status($status);
		return $mapper->insert($group);
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
	public function exclude_user($group_id, $user_id){
		$group = new data_group();
		$group->set_id($group_id);
		$user = new data_user();
		$user->set_id($user_id);
		$mapper = new mapper_group2user($this->company, $group);
		$users = $mapper->get_users();
		if(!empty($users))
			foreach($users as $us)
				$group->add_user($us);
		$group->exclude_user($user);
		return $mapper->update();
	}

	/**
	* Возвращает список групп.
	* @return array из data_group
	*/
	public function get_group($id){
		$group = (new mapper_group(model_session::get_company()))->find($id);
		if(!($group instanceof data_group))
			throw new e_model('Объект не является группой.');
		return $group;
	}

	/**
	* Возвращает список групп.
	* @return array из data_group
	*/
	public function get_groups(){
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `status`, `name`
					FROM `groups` WHERE `company_id` = :company_id  ORDER BY `name`");
		$sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
		return $sql->map(new data_group(), 'Проблема при выборке групп пользователей.');
	}
	
	/**
	* Возвращает список пользователей группы
	* @return array из data_user
	*/
	public function init_users(data_group $group){
		$mapper = new mapper_group2user($this->company, $group);	
		$users = $mapper->get_users();
		if(!empty($users))
			foreach($users as $user)
				$group->add_user($user);
	}

	/**
	* Обновляет название группы.
	* @return object data_group
	*/
	public function update_name($id, $name){
		$group = $this->get_group($id);
		$group->set_name($name);
		$mapper = new mapper_group($this->company);		
		return $mapper->update($group);
	}

	/**
	* Проверка принадлежности объекта к классу data_group.
	*/
	public static function is_data_group($group){
		if(!($group instanceof data_group))
			throw new e_model('Возвращеный объект не является группой.');
	}
}