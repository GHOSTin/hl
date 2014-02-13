<?php
class model_group{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

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
	public function add_user($group_id, $user_id){
		$group = new data_group();
		$group->set_id($group_id);
		$user = (new model_user)->get_user($user_id);
		$mapper = new mapper_group2user($this->company, $group);
		$mapper->init_users();
		$group->add_user($user);
		return $mapper->update();
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
		$mapper->init_users();
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
	* @return array
	*/
	public function get_groups(){
		return (new mapper_group($this->company))->get_groups();
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
}