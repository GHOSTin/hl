<?php
class model_meter{

	/**
	* Создает новый период.
	* @return data_meter
	*/
	public static function add_period(data_company $company, data_meter $meter){
	    self::verify_id($meter);
	    self::verify_periods($meter);
	    model_company::verify_id($company);
	    $meter_params = new data_meter();
	    $meter_params->id = $meter->id;
	    $meters = self::get_meters($company, $meter_params);
	    if(count($meters) !== 1)
	        throw new e_model('Cчетчик с таким идентификатором не существует.');
	    $new_meter = $meters[0];
	    if(array_search($meter->periods[0], $new_meter->periods) === false){
	    	$new_meter->periods[] = $meter->periods[0];
	    	sort($new_meter->periods);
	    }
	    $sql = new sql();
	    $sql->query("UPDATE `meters` SET `periods` = :periods
	    	WHERE `company_id` = :company_id AND `id` = :meter_id");
	    $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
	    $sql->bind(':periods', implode(';', $new_meter->periods), PDO::PARAM_STR);
	    $sql->bind(':meter_id', $new_meter->id, PDO::PARAM_INT);
	    $sql->execute('Проблема при добавлении периода в счетчик.');
	    $sql->close();
	    return $new_meter;
	}

	/**
	* Создает новую услугу.
	* @return data_meter
	*/
	public static function add_service(data_company $company, data_meter $meter){
	    self::verify_id($meter);
	    self::verify_service($meter);
	    model_company::verify_id($company);
	    $meter_params = new data_meter();
	    $meter_params->id = $meter->id;
	    $meters = self::get_meters($company, $meter_params);
	    if(count($meters) !== 1)
	        throw new e_model('Cчетчик с таким идентификатором не существует.');
	    $new_meter = $meters[0];
	    if(array_search($meter->service[0], $new_meter->service) === false)
	    	$new_meter->service[] = $meter->service[0];
	    $sql = new sql();
	    $sql->query("UPDATE `meters` SET `service` = :service
	    	WHERE `company_id` = :company_id AND `id` = :meter_id");
	    $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
	    $sql->bind(':service', implode(',', $new_meter->service), PDO::PARAM_STR);
	    $sql->bind(':meter_id', $new_meter->id, PDO::PARAM_INT);
	    $sql->execute('Проблема при добавлении услуги в счетчик.');
	    $sql->close();
	    return $new_meter;
	}

	/**
	* Возвращает список счетчиков
	* @return array из data_service
	*/
	public static function get_meters(data_company $company, data_meter $meter){
	    model_company::verify_id($company);
	    $sql = new sql();
	    $sql->query("SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
	    			FROM `meters` WHERE `company_id` = :company_id");
	    $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
	    if(!empty($meter->id)){
	        self::verify_id($meter);
	        $sql->query(" AND `id` = :id");
	        $sql->bind(':id', $meter->id, PDO::PARAM_INT);
	    }
	    if(!empty($meter->name)){
	        self::verify_name($meter);
	        $sql->query(" AND `name` = :name");
	        $sql->bind(':name', $meter->name, PDO::PARAM_STR);
	    }
	    if(!empty($meter->capacity)){
	        self::verify_capacity($meter);
	        $sql->query(" AND `capacity` = :capacity");
	        $sql->bind(':capacity', $meter->capacity, PDO::PARAM_INT);
	    }
	    if(!empty($meter->rates)){
	        self::verify_rates($meter);
	        $sql->query(" AND `rates` = :rates");
	        $sql->bind(':rates', $meter->rates, PDO::PARAM_INT);
	    }
	    if(!empty($meter->service)){
	        self::verify_service($meter);
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
	public static function create_meter(data_company $company, data_meter $meter){
	    self::verify_name($meter);
	    self::verify_capacity($meter);
	    self::verify_rates($meter);
	    model_company::verify_id($company);
	    if(count(self::get_meters($company, $meter)) > 0)
	    	throw new e_model('Такой счетчик уже существует.');
	    $meter->id = self::get_insert_id($company);
	    $meter->company_id = $company->id;
	    $meter->periods = [];
	    self::verify_id($meter);
	    self::verify_company_id($meter);
	    self::verify_name($meter);
	    self::verify_rates($meter);
	    self::verify_capacity($meter);
	    $sql = new sql();
	    $sql->query("INSERT INTO `meters` (`id`, `company_id`, `name`, `rates`, `capacity`,`periods`)
	                VALUES (:id, :company_id, :name, :rates, :capacity, :periods)");
	    $sql->bind(':id', $meter->id, PDO::PARAM_INT);
	    $sql->bind(':company_id', $meter->company_id, PDO::PARAM_INT);
	    $sql->bind(':name', $meter->name, PDO::PARAM_STR);
	    $sql->bind(':rates', $meter->rates, PDO::PARAM_INT);
	    $sql->bind(':capacity', $meter->capacity, PDO::PARAM_INT);
	    $sql->bind(':periods', implode(';', $meter->periods), PDO::PARAM_STR);
	    $sql->execute('Проблемы при создании счетчика.');
	    $sql->close();
	    return $meter;
	}

	/**
	* Возвращает следующий для вставки идентификатор счетчика.
	* @return int
	*/
	private static function get_insert_id(data_company $company){
	    model_company::verify_id($company);
	    $sql = new sql();
	    $sql->query("SELECT MAX(`id`) as `max_id` FROM `meters`
	                WHERE `company_id` = :company_id");
	    $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
	    $sql->execute('Проблема при опредении следующего meter_id.');
	    if($sql->count() !== 1)
	        throw new e_model('Проблема при опредении следующего meter_id.');
	    $id = (int) $sql->row()['max_id'] + 1;
	    $sql->close();
	    return $id;
	}

	/**
	* Исключает период
	* @return data_meter
	*/
	public static function remove_period(data_company $company, data_meter $meter){
	    self::verify_id($meter);
	    self::verify_service($meter);
	    model_company::verify_id($company);
	    $meters = self::get_meters($company, $meter);
	    if(count($meters) !== 1)
	        throw new e_model('Cчетчик с таким идентификатором не существует.');
	    $new_meter = $meters[0];
	    self::is_data_meter($new_meter);
		$pos = array_search($meter->periods[0], $new_meter->periods);
	    if($pos === false)
	    	throw new e_model('Период не привязан к счетчику.');
    	unset($new_meter->periods[$pos]);
    	$sql = new sql();
	    $sql->query("UPDATE `meters` SET `periods` = :periods
	    	WHERE `company_id` = :company_id AND `id` = :meter_id");
	    $sql->bind(':company_id', $company->id, PDO::PARAM_INT);
	    $sql->bind(':periods', implode(';', $new_meter->periods), PDO::PARAM_STR);
	    $sql->bind(':meter_id', $new_meter->id, PDO::PARAM_INT);
	    $sql->execute('Проблема при удалении периода из счетчика.');
	    $sql->close();
	    return $new_meter;
	}

	/**
	* Исключает услугу
	* @return data_service
	*/
	public static function remove_service(data_company $company, data_meter $meter){
	    self::verify_id($meter);
	    self::verify_service($meter);
	    model_company::verify_id($company);
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
	public static function rename_meter(data_company $company, data_meter $meter){
	    self::verify_id($meter);
	    self::verify_name($meter);
	    $meter_params = new data_meter();
	    $meter_params->name = $meter->name;
	    if(count(self::get_meters($company, $meter_params)) > 0)
	        throw new e_model('Счетчик с таким именем уже существует.');
	    $meter_params = new data_meter();
	    $meter_params->id = $meter->id;
	    $meters = self::get_meters($company, $meter_params);
	    if(count($meters) !== 1)
	        throw new e_model('Cчетчик с таким идентификатором не существует.');
	    $new_meter = $meters[0];
	    self::is_data_meter($new_meter);
	    $new_meter->name = $meter->name;
	    $sql = new sql();
	    $sql->query("UPDATE `meters` SET `name` = :name
	                WHERE `company_id` = :company_id AND `id` = :id");
	    $sql->bind(':id', $new_meter->id, PDO::PARAM_INT);
	    $sql->bind(':company_id', $new_meter->company_id, PDO::PARAM_INT);
	    $sql->bind(':name', $new_meter->name, PDO::PARAM_STR);
	    $sql->execute('Проблема при переименовании счетчика.');
	    $sql->close();
	    return $new_meter;
	}

	/**
	* Изменяет разрядность счетчика.
	* @return object data_meter
	*/
	public static function update_capacity(data_company $company, data_meter $meter){
	    self::verify_id($meter);
	    self::verify_capacity($meter);
	    $meter_params = new data_meter();
	    $meter_params->id = $meter->id;
	    $meters = self::get_meters($company, $meter_params);
	    if(count($meters) !== 1)
	        throw new e_model('Cчетчик с таким идентификатором не существует.');
	    $new_meter = $meters[0];
	    self::is_data_meter($new_meter);
	    $new_meter->capacity = $meter->capacity;
	    $sql = new sql();
	    $sql->query("UPDATE `meters` SET `capacity` = :capacity
	                WHERE `company_id` = :company_id AND `id` = :id");
	    $sql->bind(':id', $new_meter->id, PDO::PARAM_INT);
	    $sql->bind(':company_id', $new_meter->company_id, PDO::PARAM_INT);
	    $sql->bind(':capacity', $new_meter->capacity, PDO::PARAM_INT);
	    $sql->execute('Проблема при изменении разрядности счетчика.');
	    $sql->close();
	    return $new_meter;
	}

	/**
	* Изменяет тарифность счетчика.
	* @return object data_meter
	*/
	public static function update_rates(data_company $company, data_meter $meter){
	    self::verify_id($meter);
	    self::verify_rates($meter);
	    $meter_params = new data_meter();
	    $meter_params->id = $meter->id;
	    $meters = self::get_meters($company, $meter_params);
	    if(count($meters) !== 1)
	        throw new e_model('Cчетчик с таким идентификатором не существует.');
	    $new_meter = $meters[0];
	    self::is_data_meter($new_meter);
	    $new_meter->rates = $meter->rates;
	    $sql = new sql();
	    $sql->query("UPDATE `meters` SET `rates` = :rates
	                WHERE `company_id` = :company_id AND `id` = :id");
	    $sql->bind(':id', $new_meter->id, PDO::PARAM_INT);
	    $sql->bind(':company_id', $new_meter->company_id, PDO::PARAM_INT);
	    $sql->bind(':rates', $new_meter->rates, PDO::PARAM_INT);
	    $sql->execute('Проблема при изменении тарифности счетчика.');
	    $sql->close();
	    return $new_meter;
	}

	/**
	* Верификация идентификатора счетчика.
	*/
	public static function verify_company_id(data_meter $meter){
		if($meter->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}

	/**
	* Верификация времени поверки счетчика.
	*/
	public static function verify_chektime(data_meter $meter){
		if($meter->chektime < 0)
			throw new e_model('Время поверки счетчика задано не верно.');
	}

	/**
	* Верификация идентификатора счетчика.
	*/
	public static function verify_id(data_meter $meter){
		if($meter->id < 1)
			throw new e_model('Идентификатор счетчика задан не верно.');
	}

	/**
	* Верификация названия счетчика.
	*/
	public static function verify_name(data_meter $meter){
		if(!preg_match('/^[а-яА-Яa-zA-Z0-9 -]+$/u', $meter->name))
			throw new e_model('Название счетчика задано не верно.');
	}

	/**
	* Верификация разрядности счетчика.
	*/
	public static function verify_capacity(data_meter $meter){
		if($meter->capacity < 1 OR $meter->capacity > 9)
			throw new e_model('Разрядность задана не верно.');
	}

	/**
	* Верификация тарифности счетчика.
	*/
	public static function verify_rates(data_meter $meter){
		if($meter->rates < 1 OR $meter->rates > 3)
			throw new e_model('Тарифность задана не верно.');
	}

	/**
	* Верификация периодов счетчика.
	*/
	public static function verify_periods(data_meter $meter){
		foreach($meter->periods as $period)
			$period = (int) ($period);
			if($period < 0 OR $period > 240)
				throw new e_model('Период задан не верно.');
	}

	/**
	* Верификация услуги счетчика.
	*/
	public static function verify_service(data_meter $meter){
		$services = ['cold_water', 'hot_water', 'electrical'];
		foreach($meter->service as $service)
			if(array_search($service, $services) === false)
				throw new e_model('Услуга задана не верно.');
	}

	/**
    * Верификация заводского номера счетчика.
    */
    public static function verify_serial(data_meter $meter){
        if(!preg_match('/^[а-яА-Я0-9]+$/u', $meter->serial))
            throw new e_model('Заводской номер счетчика задано не верно.');
    }

	/**
	* Проверка принадлежности объекта к классу data_meter.
	*/
	public static function is_data_meter($meter){
		if(!($meter instanceof data_meter))
			throw new e_model('Возвращеный объект не является счетчиком.');
	}
}