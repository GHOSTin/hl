<?php
class model_meter{

	/**
	* Возвращает список услуг
	* @return array из data_service
	*/
	public static function get_meters(data_company $company, data_meter $meter){
	    model_company::verify_id($company);
	    $sql = new sql();
	    $sql->query("SELECT `id`, `company_id`, `name`, `capacity`, `rates`
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
	    self::verify_id($meter);
	    self::verify_company_id($meter);
	    self::verify_name($meter);
	    self::verify_rates($meter);
	    self::verify_capacity($meter);
	    $sql = new sql();
	    $sql->query("INSERT INTO `meters` (`id`, `company_id`, `name`, `rates`, `capacity`)
	                VALUES (:id, :company_id, :name, :rates, :capacity)");
	    $sql->bind(':id', $meter->id, PDO::PARAM_INT);
	    $sql->bind(':company_id', $meter->company_id, PDO::PARAM_INT);
	    $sql->bind(':name', $meter->name, PDO::PARAM_STR);
	    $sql->bind(':rates', $meter->rates, PDO::PARAM_INT);
	    $sql->bind(':capacity', $meter->capacity, PDO::PARAM_INT);
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
		if($meter->capacity < 1)
			throw new e_model('Разрядность задана не верно.');
	}

	/**
	* Верификация тарифности счетчика.
	*/
	public static function verify_rates(data_meter $meter){
		if($meter->rates < 1)
			throw new e_model('Тарифность задана не верно.');
	}

	/**
	* Проверка принадлежности объекта к классу data_meter.
	*/
	public static function is_data_meter($meter){
		if(!($meter instanceof data_meter))
			throw new e_model('Возвращеный объект не является счетчиком.');
	}
}