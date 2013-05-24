<?php
class model_meter{

	/**
	* Возвращает список услуг
	* @return array из data_service
	*/
	public static function get_meters(data_company $company, data_meter $meter){
	    model_company::verify_id($company);
	    $sql = new sql();
	    $sql->query("SELECT `id`, `company_id`, `name` FROM `meters`
	        WHERE `company_id` = :company_id");
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
	    return $sql->map(new data_meter(), 'Проблема при выборке счетчиков.');
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
		if(empty($meter->name))
			throw new e_model('Название счетчика задано не верно.');
	}
	/**
	* Верификация серийного номера счетчика.
	*/
	public static function verify_serial(data_meter $meter){
		if(empty($meter->serial))
			throw new e_model('Серийный номер счетчика задан не верно.');
	}
	/**
	* Верификация названия службы.
	*/
	public static function verify_service(data_meter $meter){
		if(empty($meter->service))
			throw new e_model('Название службы задано не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_meter.
	*/
	public static function is_data_meter($meter){
		if(!($meter instanceof data_meter))
			throw new e_model('Возвращеный объект не является счетчиком.');
	}
}