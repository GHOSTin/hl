<?php
class model_user{

	public function create_user($lastname, $firstname, $middlename, $login,
		$password, $status){
		$mapper = di::get('mapper_user');
		if(!is_null($mapper->find_user_by_login($login)))
      throw new RuntimeException();
		$user = new data_user();
		$user->set_id($mapper->get_insert_id());
		$user->set_lastname($lastname);
		$user->set_firstname($firstname);
		$user->set_middlename($middlename);
		$user->set_login($login);
		$user->set_hash($this->get_password_hash($password));
		$user->set_status($status);
		return $mapper->insert($user);
	}

	public function get_password_hash($password){
		return md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
	}

	public function get_user($id){
		$user = di::get('mapper_user')->find($id);
		if(!($user instanceof data_user))
			throw new e_model('Не существует такого пользователя.');
		return $user;
	}

	public function get_users(){
		return di::get('mapper_user')->get_users();
	}

	public function update_fio($id, $lastname, $firstname, $middlename){
		$mapper = di::get('mapper_user');
		$user = $mapper->find($id);
		$user->set_lastname($lastname);
		$user->set_firstname($firstname);
		$user->set_middlename($middlename);
		$mapper->update($user);
		return $user;
	}

	public function update_password($id, $password){
		data_user::verify_password($password);
    $mapper = di::get('mapper_user');
		$user = $mapper->find($id);
		$user->set_hash($this->get_password_hash($password));
		$mapper->update($user);
		return $user;
	}

	public function update_login($id, $login){
		$mapper = di::get('mapper_user');
		if(!is_null($mapper->find_user_by_login($login)))
			throw new RuntimeException();
		$user = $mapper->find($id);
		$user->set_login($login);
		$mapper->update($user);
		return $user;
	}

	public function update_user_status($id){
		$mapper = di::get('mapper_user');
		$user = $mapper->find($id);
		if($user->get_status() === 'true')
			$user->set_status('false');
		else
			$user->set_status('true');
		$mapper->update($user);
		return $user;
	}

	public static function update_cellphone($id, $cellphone){
		$mapper = di::get('mapper_user');
		$user = $mapper->find($id);
		$user->set_cellphone($cellphone);
		$mapper->update($user);
		return $user;
	}

	public function update_telephone($id, $telephone){
		$mapper = di::get('mapper_user');
		$user = $mapper->find($id);
		$user->set_telephone($telephone);
		$mapper->update($user);
		return $user;
	}
}