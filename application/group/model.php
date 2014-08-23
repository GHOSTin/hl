<?php
class model_group{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}

	public function create_group($name, $status){
		$em = di::get('em');
		if(!is_null($em->getRepository('data_group')->findOneByName($name)))
			throw new RuntimeException('Группа с таким название уже существует.');
		$group = new data_group();
		$group->set_company_id(di::get('company')->get_id());
		$group->set_name($name);
		$group->set_status($status);
		$em->persist($group);
		$em->flush();
		return $group;
	}

	public function add_user($group_id, $user_id){
		$em = di::get('em');
		$group = $em->find('data_group', $group_id);
		$user = $em->find('data_user', $user_id);
		$group->add_user($user);
		$em->flush();
		return $group;
	}

	public function exclude_user($group_id, $user_id){
		$em = di::get('em');
		$group = $em->find('data_group', $group_id);
		$user = $em->find('data_user', $user_id);
		$group->exclude_user($user);
		$em->flush();
		return $group;
	}

	public function update_name($id, $name){
		$em = di::get('em');
		$group = $em->find('data_group', $id);
		$group->set_name($name);
		$em->flush();
		return $group;
	}
}