<?php
class model_meter{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}

	/**
	* Создает новый период.
	* @return data_meter
	*/
	public function add_period($id, $period){
		$meter = $this->get_meter($id);
		$meter->add_period($period);
		$mapper = new mapper_meter($this->company);
		return $mapper->update($meter);
	}

	/**
	* Создает новую услугу.
	* @return data_meter
	*/
	public function add_service($id, $service){
	    $meter = $this->get_meter($id);
		$meter->add_service($service);
		$mapper = new mapper_meter($this->company);
		return $mapper->update($meter);
	}

	/*
	* Возвращает объект счетчика ли падает с исключением что счетчик не существует.
	*/
	public function get_meter($id){
		$mapper = new mapper_meter($this->company);
		$meter = $mapper->find($id);
		$this->is_data_meter($meter);
		return $meter;
	}

	/**
	* Возвращает список счетчиков
	* @return array из data_service
	*/
	public function get_meters(data_meter $meter){
	    $this->company->verify('id');
	    $sql = new sql();
	    $sql->query("SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
	    			FROM `meters` WHERE `company_id` = :company_id");
	    $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
	    if(!empty($meter->id)){
	        die('Disabled filtering by ID');
	    }
	    if(!empty($meter->name)){
	        $meter->verify('name');
	        $sql->query(" AND `name` = :name");
	        $sql->bind(':name', $meter->name, PDO::PARAM_STR);
	    }
	    if(!empty($meter->capacity)){
	        $meter->verify('capacity');
	        $sql->query(" AND `capacity` = :capacity");
	        $sql->bind(':capacity', $meter->capacity, PDO::PARAM_INT);
	    }
	    if(!empty($meter->rates)){
	        $meter->verify('rates');
	        $sql->query(" AND `rates` = :rates");
	        $sql->bind(':rates', $meter->rates, PDO::PARAM_INT);
	    }
	    if(!empty($meter->service)){
	        $meter->verify('service');
	        $sql->query(" AND FIND_IN_SET(:service, `service`) > 0");
	        $sql->bind(':service', $meter->service[0], PDO::PARAM_INT);
	    }
	    $sql->query(' ORDER BY `name`');
	    return $sql->map(new data_meter(), 'Проблема при выборке счетчиков.');
	}

	/**
	* Создает новый счетчик
	* @return data_meter
	*/
	public function create_meter($name, $capacity, $rates){
		$mapper = new mapper_meter($this->company);
		if(!is_null($mapper->find_by_name($name)))
			throw new e_model('Счетчик с таким именем уже существует');
		$meter = new data_meter();
		$meter->set_name($name);
		$meter->set_capacity($capacity);
		$meter->set_rates($rates);
		return $mapper->create($meter);
	}

	/**
	* Исключает период
	* @return data_meter
	*/
	public function remove_period($id, $period){
	    $meter = $this->get_meter($id);
		$meter->remove_period($period);
		$mapper = new mapper_meter($this->company);
		return $mapper->update($meter);
	}

	/**
	* Исключает услугу
	* @return data_service
	*/
	public static function remove_service(data_company $company, data_meter $meter){
	    $meter->verify('id', 'service');
	    $company->verify('id');
	    $meters = self::get_meters($company, $meter);
	    if(count($meters) !== 1)
	        throw new e_model('Cчетчик с таким идентификатором не существует.');
	    $new_meter = $meters[0];
	    self::is_data_meter($new_meter);
		$pos = array_search($meter->service[0], $new_meter->service);
	    if($pos === false)
	    	throw new e_model('Услуга не привязана к счетчику.');
    	unset($new_meter->service[$pos]);
    	$sql = new sql();
	    $sql->query("UPDATE `meters` SET `service` = :service
	    	WHERE `company_id` = :company_id AND `id` = :meter_id");
	    $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
	    $sql->bind(':service', implode(',', $new_meter->service), PDO::PARAM_STR);
	    $sql->bind(':meter_id', $new_meter->id, PDO::PARAM_INT);
	    $sql->execute('Проблема при удалении услуги из счетчика.');
	    $sql->close();
	    return $new_meter;
	}

	/**
	* Создает новую услугу
	* @return data_service
	*/
	public function rename_meter($id, $name){
		$meter = $this->get_meter($id);
		$mapper = new mapper_meter($this->company);
		$old_meter = $mapper->find_by_name($name);
		if(!is_null($old_meter))
			if($meter->id != $old_meter->id)
				throw new e_model('Счетчик с таким именем уже существует.');
		$meter->set_name($name);
		$mapper->update($meter);
		return $meter;
	}

	/**
	* Изменяет разрядность счетчика.
	* @return object data_meter
	*/
	public function update_capacity($id, $capacity){
		$mapper = new mapper_meter($this->company);
		$meter = $this->get_meter($id);
		$meter->set_capacity($capacity);
		$mapper->update($meter);
		return $meter;
	}

	/**
	* Изменяет тарифность счетчика.
	* @return object data_meter
	*/
	public function update_rates($id, $rates){
	    $mapper = new mapper_meter($this->company);
		$meter = $this->get_meter($id);
		$meter->set_rates($rates);
		$mapper->update($meter);
		return $meter;
	}

	/**
	* Проверка принадлежности объекта к классу data_meter.
	*/
	public static function is_data_meter($meter){
	    if(!($meter instanceof data_meter))
	        throw new e_model('Возвращеный объект не является счетчиком.');
	}
}