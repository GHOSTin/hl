<?php
class model_number{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}
	
	public function get_number($id){
		$mapper = new mapper_number($this->company);
		$number = $mapper->find($id);
		if(!($number instanceof data_number))
			throw new e_model('Счетчика не существует.');			
		return $number;
	}

	/**
	* Обновляет номер лицевого счета
	* @return object data_number
	*/
	public function update_number($id, $num){
		$number = $this->get_number($id);
		$mapper = new mapper_number($this->company);
		$old_number = $mapper->find_by_number($num);
		if(!is_null($old_number))
			if($number->get_id() != $old_number->get_id())
				throw new e_model('В базе уже есть лицевой счет с таким номером.');
		$number->set_number($num);
		$mapper->update($number);
		return $number;
	}

	/**
	* Обновляет номер лицевого счета
	* @return object data_number
	*/
	public function update_password($id, $password){
		$number = $this->get_number($id);
		$mapper = new mapper_number($this->company);
		$hash = md5(md5(htmlspecialchars($password)).application_configuration::authSalt);
		$number->set_hash($hash);
		$mapper->update($number);
		return $number;
	}

	/**
	* Обновляет ФИО владельца лицевого счета
	* @return object data_number
	*/
	public function update_number_fio($id, $fio){
		$number = $this->get_number($id);
		$number->set_fio($fio);
		$mapper = new mapper_number($this->company);
		$mapper->update($number);
		return $number;
	}

	/**
	* Обновляет сотовый телефон владельца лицевого счета
	* @return object data_number
	*/
	public function update_number_cellphone($id, $cellphone){
		$number = $this->get_number($id);
		$number->set_cellphone($cellphone);
		$mapper = new mapper_number($this->company);
		$mapper->update($number);
		return $number;
	}

	/**
	* Обновляет телефон владельца лицевого счета
	* @return object data_number
	*/
	public function update_number_telephone($id, $telephone){
		$number = $this->get_number($id);
		$number->set_telephone($telephone);
		$mapper = new mapper_number($this->company);
		$mapper->update($number);
		return $number;
	}
}