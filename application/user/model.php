<?php
class model_user{

	public function create_user($lastname, $firstname, $middlename, $login,
		$password, $status){
		$em = di::get('em');
		if(!is_null($em->getRepository('data_user')->findOneByLogin($login)))
      throw new RuntimeException();
		$user = new data_user();
		$user->set_lastname($lastname);
		$user->set_firstname($firstname);
		$user->set_middlename($middlename);
		$user->set_login($login);
		$user->set_hash($this->get_password_hash($password));
		$user->set_status($status);
		$em->flush();
		return $user;
	}

	public function get_password_hash($password){
		return md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
	}

	public function update_fio($id, $lastname, $firstname, $middlename){
		$em = di::get('em');
		$user = $em->find('data_user', $id);
		$user->set_lastname($lastname);
		$user->set_firstname($firstname);
		$user->set_middlename($middlename);
		$em->flush();
		return $user;
	}

	public function update_password($id, $password){
    $em = di::get('em');
		$user = $em->find('data_user', $id);
		$user->set_hash($this->get_password_hash($password));
		$em->flush();
		return $user;
	}

	public function update_login($id, $login){
		$em = di::get('em');
		$user = $em->getRepository('data_user')->findOneByLogin($login);
		if(!is_null($user))
			throw new RuntimeException();
		$user = $em->find('data_user', $id);
		$user->set_login($login);
		$em->flush();
		return $user;
	}

	public function update_user_status($id){
		$em = di::get('em');
		$user = $em->find('data_user', $id);
		if($user->get_status() === 'true')
			$user->set_status('false');
		else
			$user->set_status('true');
		$em->flush();
		return $user;
	}

	public static function update_cellphone($id, $cellphone){
		$em = di::get('em');
		$user = $em->find('data_user', $id);;
		$user->set_cellphone($cellphone);
		$em->flush();
		return $user;
	}

	public function update_telephone($id, $telephone){
		$em = di::get('em');
		$user = $em->find('data_user', $id);
		$user->set_telephone($telephone);
		$em->flush();
		return $user;
	}
}