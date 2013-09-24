<?php
class model_number{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}
	
	/**
	* Создает новый лицевой ссчет уникальный для компании и для города.
	* @return object data_number
	*/
	public static function DELETE__create_number(data_company $company, data_city $city, data_flat $flat,
										data_number $number){
		$city->verify('id');
		$number->id = self::get_insert_id($city);
		$number->company_id = $company->id;
		$number->city_id = $city->id;
		$number->type = 'human';
		$number->house_id = $flat->house_id;
		$number->flat_id = $flat->id;
		$number->verify('id', 'company_id', 'citu_id', 'house_id', 'flat_id', 'number',
						'type', 'status', 'fio');
		$sql = new sql();
		$sql->query("INSERT INTO `numbers` (`id`, `company_id`, `city_id`, `house_id`,
					`flat_id`, `number`, `type`, `status`, `fio`, `telephone`, `cellphone`,
					`password`, `contact-fio`, `contact-telephone`, `contact-cellphone`)
					VALUES (:number_id, :company_id, :city_id, :house_id, :flat_id,
					:number, :type, :status, :fio, :telephone, :cellphone, :password,
					:contact_fio, :contact_telephone, :contact_cellphone)");
		$sql->bind(':number_id', $number->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $number->company_id, PDO::PARAM_INT);
		$sql->bind(':city_id', $number->city_id, PDO::PARAM_INT);
		$sql->bind(':house_id', $number->house_id, PDO::PARAM_INT);
		$sql->bind(':flat_id', $number->flat_id, PDO::PARAM_INT);
		$sql->bind(':number', $number->number, PDO::PARAM_STR);
		$sql->bind(':type', $number->type, PDO::PARAM_STR);
		$sql->bind(':status', $number->status, PDO::PARAM_STR);
		$sql->bind(':fio', $number->fio, PDO::PARAM_STR);
		$sql->bind(':telephone', $number->telephone, PDO::PARAM_STR);
		$sql->bind(':cellphone', $number->cellphone, PDO::PARAM_STR);
		$sql->bind(':password', $number->password, PDO::PARAM_STR);
		$sql->bind(':contact_fio', $number->contact_fio, PDO::PARAM_STR);
		$sql->bind(':contact_telephone', $number->contact_telephone, PDO::PARAM_STR);
		$sql->bind(':contact_cellphone', $number->contact_cellphone, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании нового лицевого счета.');
		return $number;
	}

	/**
	* Возвращает следующий для вставки идентификатор лицевого счета.
	* @return int
	*/
	public static function DELETE__get_insert_id(data_company $company, data_city $city){
		$company->verify('id');
		$city->verify('id');
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_number_id` FROM `numbers`
					WHERE `company_id` = :company_id AND `city_id` = :city_id");
		$sql->bind(':city_id', $city->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Проблема при опредении следующего number_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего number_id.');
		$number_id = (int) $sql->row()['max_number_id'] + 1;
		return $number_id;
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