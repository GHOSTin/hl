<?php
class model_city{

	/**
	* Создает новый дом.
	* @return object data_city
	*/
	public static function create_city(data_city $city, data_current_user $user){
		$city->company_id = $user->company_id;
		$city->id = self::get_insert_id();
		$city->verify('id','name', 'company_id', 'status');
		$sql = new sql();
		$sql->query("INSERT INTO `cities` (`id`, `company_id`, `status`, `name`)
					VALUES (:city_id, :company_id, :status, :name)");
		$sql->bind(':city_id', $city->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $city->company_id, PDO::PARAM_INT);
		$sql->bind(':status', $city->status, PDO::PARAM_STR);
		$sql->bind(':name', $city->name, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании города.');
		$stm->close();
		return $city;
	}

	/**
	* Создает новую улицу.
	* @return object data_street
	*/
	public static function create_street(data_city $city, data_street $street, data_current_user $user){
		$street->verify('name');
		$city->verify('id');
		$cities = self::get_cities($city);
		if(count($cities) !== 1)
			throw new e_model('Проблемы при выборке города.');
		$city = $cities[0];
		self::is_data_city($city);
		$streets = self::get_streets($city, $street);
		if(count($streets) > 0)
			throw new e_model('Улица уже существует.');
		$street->company_id = $user->company_id;
		$street->city_id = $city->id;
		$street->id = model_street::get_insert_id();
		$street->verify('id', 'company_id', 'city_id', 'name', 'status');
		$sql = new sql();
		$sql->query("INSERT INTO `streets` (`id`, `company_id`, `city_id`, `status`, `name`)
					VALUES (:street_id, :company_id, :city_id, :status, :name)");
		$sql->bind(':company_id', $street->company_id, PDO::PARAM_INT);
		$sql->bind(':street_id', $street->id, PDO::PARAM_INT);
		$sql->bind(':city_id', $street->city_id, PDO::PARAM_INT);
		$sql->bind(':status', $street->status, PDO::PARAM_STR);
		$sql->bind(':name', $street->name, PDO::PARAM_STR);
		$sql->execute('Проблемы при вставке улицы в базу данных.');
		$sql->close();
		return $street;
	}

	/**
	* Создает новую улицу.
	* @return object data_street
	*/
	public static function create_number(data_company $company, data_city $city,
				 	data_street $street, data_house $house, data_flat $flat, data_number $number ){
		$company->verify('id');
		$city->verify('id');
		$street->verify('id');
		$flat->verify('id');
		$number->verify('number', 'fio');
		$cities = self::get_cities($city);
		if(count($cities) !== 1)
			throw new e_model('Проблемы при выборке города.');
		$city = $cities[0];
		self::is_data_city($city);
		$streets = self::get_streets($city, $street);
		if(count($streets) !== 1)
			throw new e_model('Проблемы при выборке улицы.');
		$street = $streets[0];
		model_street::is_data_street($street);
		$houses = model_street::get_houses($street, $house);
		if(count($houses) !== 1)
			throw new e_model('Проблемы при выборке дома.');
		$house = $houses[0];
		model_house::is_data_house($house);
		$flats = model_house::get_flats($house, $flat);
		if(count($flats) !== 1)
			throw new e_model('Проблемы при выборке квартиры.');
		$flat = $flats[0];
		model_flat::is_data_flat($flat);
		if(count(self::get_numbers($company, $city, $number)) !== 0)
			throw new e_model('Такой лицевой уже есть в базе.');
		$number->id = model_number::get_insert_id($company, $city);
		$number->company_id = $company->id;
		$number->city_id = $city->id;
		$number->house_id = $house->id;
		$number->flat_id = $flat->id;
		$number->verify('id', 'company_id', 'city_id', 'house_id', 'flat_id', 'number', 'fio');
		$sql = new sql();
		$sql->query("INSERT INTO `numbers` (`id`, `company_id`, `city_id`, `house_id`, 
					`flat_id`, `number`, `type`, `status`, `fio`)
					VALUES (:id, :company_id, :city_id, :house_id, :flat_id,
					:number, 'human', true, :fio)");
		$sql->bind(':id', $number->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $number->company_id, PDO::PARAM_INT);
		$sql->bind(':city_id', $number->city_id, PDO::PARAM_INT);
		$sql->bind(':house_id', $number->house_id, PDO::PARAM_INT);
		$sql->bind(':flat_id', $number->flat_id, PDO::PARAM_INT);
		$sql->bind(':number', $number->number, PDO::PARAM_STR);
		$sql->bind(':fio', $number->fio, PDO::PARAM_STR);
		$sql->execute('Проблемы при вставке лицевого счета в базу данных.');
		$sql->close();
		return $number;
	}

	/**
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	private static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_city_id` FROM `cities`");
		$sql->execute('Проблема при опредении следующего city_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего city_id.');
		$city_id = (int) $sql->row()['max_city_id'] + 1;
		$sql->close();
		return $city_id;
	}

	/*
	* Возвращает список городов
	*/
	public static function get_cities(data_city $city){
		$sql = new sql();
		$sql->query("SELECT `id`, `status`, `name` FROM `cities`");
		if(!empty($city->name)){
			$city->verify('name');
			$sql->query(" WHERE `name` = :name");
			$sql->bind(':name', $city->name, PDO::PARAM_STR);
		}
		return $sql->map(new data_city(), 'Проблема при выборке городов.');
	}

	/*
	* Возвращает список улиц города
	*/
	public static function get_streets(data_city $city, data_street $street){
		self::verify_id($city);
		$sql = new sql();
		$sql->query("SELECT `id`, `city_id`, `status`, `name`
					FROM `streets` WHERE `city_id` = :city_id");
		$sql->bind(':city_id', $city->id, PDO::PARAM_STR);
		if(!empty($street->name)){
			$street->verify('name');
			$sql->query(" AND `name` = :name");
			$sql->bind(':name', $street->name, PDO::PARAM_STR);
		}
		return $sql->map(new data_street(), 'Проблема при выборке улиц города.');
	}

	/*
	* Возвращает список улиц города
	*/
	public static function get_numbers(data_company $company, data_city $city, data_number $number){
		$city->verify('id');
		$number->verify('number');
		$company->verify('id');
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
		$sql->bind(':city_id', $city->id, PDO::PARAM_INT);
		$sql->bind(':number', $number->number, PDO::PARAM_INT);
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		return $sql->map(new data_number(), 'Проблема при выборке лицевых счетов.');
	}
	
	/**
	* Проверка принадлежности объекта к классу data_city.
	*/
	public static function is_data_city($city){
		if(!($city instanceof data_city))
			throw new e_model('Возвращеный объект не является городом.');
	}
}