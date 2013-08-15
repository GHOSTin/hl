<?php
class model_meter{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}

	/**
	* Добавляет в счетчик новую период поверки.
	* @return data_meter
	*/
	public function add_period($id, $period){
		$meter = $this->get_meter($id);
		$meter->add_period($period);
		$mapper = new mapper_meter($this->company);
		return $mapper->update($meter);
	}

	/**
	* Добавляет в счетчик новую услугу.
	* @return data_meter
	*/
	public function add_service($id, $service){
	    $meter = $this->get_meter($id);
		$meter->add_service($service);
		$mapper = new mapper_meter($this->company);
		return $mapper->update($meter);
	}

	/*
	* Возвращает объект счетчика или падает с исключением, что счетчик не существует.
	*/
	public function get_meter($id){
		$mapper = new mapper_meter($this->company);
		$meter = $mapper->find($id);
		if(!($meter instanceof data_meter))
			throw new e_model('Счетчика не существует.');
		return $meter;
	}

	/**
	* Возвращает список счетчиков
	* @return array data_meter
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
	        die('Disabled filtering by name');
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
	        die('Disabled filtering by service');
	    }
	    $sql->query(' ORDER BY `name`');
	    return $sql->map(new data_meter(), 'Проблема при выборке счетчиков.');
	}

	/**
	* Возвращает список счетчиков
	* @return array data_meter
	*/
	public function get_meters_by_service($service){
	    $this->company->verify('id');
	    $sql = new sql();
	    $sql->query("SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
	    			FROM `meters` WHERE `company_id` = :company_id AND FIND_IN_SET(:service, `service`) > 0
	    		 	ORDER BY `name`");
	    $sql->bind(':company_id', (int) $this->company->id, PDO::PARAM_INT);
	    $sql->bind(':service', (string) $service, PDO::PARAM_STR);
	    return $sql->map(new data_meter(), 'Проблема при выборке счетчиков.');
	}

	/**
	* Создает новый счетчик.
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
	* Удаляет привязку услуги к счетчику.
	* @return data_meter
	*/
	public function remove_service($id, $service){
	    $meter = $this->get_meter($id);
		$meter->remove_service($service);
		$mapper = new mapper_meter($this->company);
		return $mapper->update($meter);
	}

	/**
	* Переименовывает счетчик.
	* @return data_meter
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
}