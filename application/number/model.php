<?php
class model_number{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}

	public function get_number($id){
		$number = di::get('mapper_number')->find($id);
		if(!($number instanceof data_number))
			throw new RuntimeException('Счетчика не существует.');
		return $number;
	}

	public function update_number($id, $num){
		$number = $this->get_number($id);
		$mapper = di::get('mapper_number');
		$old_number = $mapper->find_by_number($num);
		if(!is_null($old_number))
			if($number->get_id() != $old_number->get_id())
				throw new RuntimeException('В базе уже есть лицевой счет с таким номером.');
		$number->set_number($num);
		$mapper->update($number);
		return $number;
	}

	public function update_password($id, $password){
		$number = $this->get_number($id);
		$mapper = di::get('mapper_number');
		$hash = md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
		$number->set_hash($hash);
		$mapper->update($number);
		return $number;
	}

	public function update_number_fio($id, $fio){
		$number = $this->get_number($id);
		$number->set_fio($fio);
		$mapper = di::get('mapper_number');
		$mapper->update($number);
		return $number;
	}

	public function update_number_cellphone($id, $cellphone){
		$number = $this->get_number($id);
		$number->set_cellphone($cellphone);
		$mapper = di::get('mapper_number');
		$mapper->update($number);
		return $number;
	}

	public function update_number_email($id, $email){
		$number = $this->get_number($id);
		$number->set_email($email);
		$mapper = di::get('mapper_number');
		$mapper->update($number);
		return $number;
	}

	public function update_number_telephone($id, $telephone){
		$number = $this->get_number($id);
		$number->set_telephone($telephone);
		$mapper = di::get('mapper_number');
		$mapper->update($number);
		return $number;
	}
}