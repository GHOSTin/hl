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
		$meter = (new mapper_meter($this->company))->find($id);
		if(!($meter instanceof data_meter))
			throw new e_model('Счетчика не существует.');
		return $meter;
	}

	/**
	* Возвращает список счетчиков
	* @return array data_meter
	*/
	public function get_meters(){
		return (new mapper_meter($this->company))->get_meters();
	}

	/**
	* Возвращает список счетчиков
	* @return array data_meter
	*/
	public function get_meters_by_service($service){
	    return (new mapper_meter($this->company))->get_meters_by_service($service);
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
			if($meter->get_id() != $old_meter->get_id())
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