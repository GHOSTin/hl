<?php
class model_city{
	/**
	* Создает новый дом.
	* @return object data_city
	*/
	public static function create_city(data_city $city, data_current_user $current_user){
		$city->company_id = $current_user->company_id;
		$city->id = self::get_insert_id();
		self::verify_city_status($city_params);
		self::verify_city_name($city_params);
		self::verify_city_company_id($city_params);
		self::verify_city_id($city_params);
		$sql = new sql();
		$sql->query("INSERT INTO `cities` (`id`, `company_id`, `status`, `name`)
					VALUES (:city_id, :company_id, :status, :name)");
		$sql->bind(':city_id', $city->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $city->company_id, PDO::PARAM_STR);
		$sql->bind(':status', $city->status, PDO::PARAM_INT);
		$sql->bind(':name', $city->name, PDO::PARAM_INT);
		$sql->execute('Проблемы при создании города.');
		$stm->close();
		return $city;
	}
	/**
	* Создает новую улицу.
	* @return object data_street
	*/
	public static function create_street(data_city $city, data_street $street, data_current_user $current_user){
		model_street::verify_street_status($street);
		model_street::verify_street_name($street);
		self::verify_city_id($city_params);
		$cities = self::get_cities($city);
		if(count($cities) !== 1)
			throw new e_model('Проблемы при выборке города.');
		$city = $cities[0];
		self::is_data_city($city);
		$streets = self::get_streets($city, $street);
		if(count($streets) > 0)
			throw new e_model('Улица уже существует.');
		$street->company_id = $current_user->company_id;
		$street->city_id = $city->id;
		$street->id = model_street::get_insert_id();
		model_street::verify_street_company_id($street);
		model_street::verify_street_city_id($street);
		model_street::verify_street_id($street);
		$sql = new sql();
		$sql->query("INSERT INTO `streets` (`id`, `company_id`, `city_id`, `status`, `name`)
					VALUES (:street_id, :company_id, :city_id, :status, :name)");
		$sql->bind(':street_id', $street->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $street->company_id, PDO::PARAM_INT);
		$sql->bind(':city_id', $street->city_id, PDO::PARAM_INT);
		$sql->bind(':status', $street->status, PDO::PARAM_STR);
		$sql->bind(':name', $street->name, PDO::PARAM_STR);
		$sql->execute('Проблемы при вставке улицы в базу данных.');
		$stm->close();
		return $street;
	}
	/**
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	private static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_city_id` FROM `cities`");
		$sql->execute('Проблема при опредении следующего city_id.');
		if($sql->count() === 1)
			throw new e_model('Проблема при опредении следующего city_id.');
		$city_id = (int) $sql->row()['max_city_id'] + 1;
		$sql->close();
		return $city_id;
	}
	/*
	* Возвращает список городов
	*/
	public static function get_cities(data_city $city_params){
		$sql = new sql();
		$sql->query("SELECT `id`, `status`, `name` FROM `cities`");
		if(!empty($city_params->name)){
			$sql->query(" WHERE `name` = :name");
			$sql->bind(':name', $city_params->name, PDO::PARAM_STR);
		}
		return $sql->map(new data_city(), 'Проблема при выборке городов.');
	}
	/*
	* Возвращает список улиц города
	*/
	public static function get_streets(data_city $city_params, data_street $street_params){
		self::verify_city_id($city_params);
		$sql = new sql();
		$sql->query("SELECT `id`, `city_id`, `status`, `name`
					FROM `streets` WHERE `city_id` = :city_id");
		if(!empty($street_params->name)){
			$sql->query(" AND `name` = :name");
			$sql->bind(':name', $street_params->name, PDO::PARAM_STR);
		}
		return $sql->map(new data_street(), 'Проблема при выборке улиц города.');
	}
	/*
	* Возвращает список улиц города
	*/
	public static function get_numbers(data_city $city_params,
		data_number $number_params, data_current_user $current_user){
		self::verify_city_id($city_params);
		model_number::verify_number_number($number_params);
		model_user::verify_user_company_id($current_user);
		$sql = new sql();
		$sql->query("SELECT `numbers`.`id`, `numbers`.`company_id`, 
						`numbers`.`city_id`, `numbers`.`house_id`, 
						`numbers`.`flat_id`, `numbers`.`number`,
						`numbers`.`type`, `numbers`.`status`,
						`numbers`.`fio`, `numbers`.`telephone`,
						`numbers`.`cellphone`, `numbers`.`password`,
						`numbers`.`contact-fio` as `contact_fio`,
						`numbers`.`contact-telephone` as `contact_telephone`,
						`numbers`.`contact-cellphone` as `contact_cellphone`,
						`flats`.`flatnumber` as `flat_number`,
						`houses`.`housenumber` as `house_number`,
						`houses`.`department_id`,
						`streets`.`name` as `street_name`
					FROM `numbers`, `flats`, `houses`, `streets`
					WHERE `numbers`.`company_id` = :company_id
					AND `numbers`.`number` = :number
					AND `numbers`.`city_id` = :city_id
					AND `numbers`.`flat_id` = `flats`.`id`
					AND `numbers`.`house_id` = `houses`.`id`
					AND `houses`.`street_id` = `streets`.`id`");
		$sql->bind(':city_id', $city_params->id, PDO::PARAM_INT);
		$sql->bind(':number', $number_params->number, PDO::PARAM_INT);
		$sql->bind(':company_id', $current_user->company_id, PDO::PARAM_INT);
		return $sql->map(new data_number(), 'Проблема при выборке лицевых счетов.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_city $city){
		if($city->company_id < 1)
			throw new e_model('Идентификатор компании города задан не верно.');
	}
	/**
	* Верификация идентификатора города.
	*/
	public static function verify_id(data_city $city){
		if($city->id < 1)
			throw new e_model('Идентификатор города задан не верно.');
	}
	/**
	* Верификация названия города.
	*/
	public static function verify_name(data_city $city){
		if(empty($city->name))
			throw new e_model('Название города задано не верно.');
	}
	/**
	* Верификация статуса города.
	*/
	public static function verify_status(data_city $city){
		if(empty($city->status))
			throw new e_model('Статус города задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_city.
	*/
	public static function is_data_city($city){
		if(!($city instanceof data_city))
			throw new e_model('Возвращеный объект не является городом.');
	}
}