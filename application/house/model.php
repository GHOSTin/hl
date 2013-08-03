<?php
class model_house{
	/**
	* Создает новую квартиру.
	* @return object data_flat
	*/
	public static function create_flat(data_house $house, data_flat $flat){
		if(count(self::get_flats($house, $flat)) > 0)
			throw new e_model('Квартира уже существует!');
		$flat->house_id = $house->id;
		$flat->id = model_flat::get_insert_id();
		model_flat::verify_id($flat);
		model_flat::verify_house_id($flat);
		model_flat::verify_status($flat);
		model_flat::verify_number($flat);
		$sql = new sql();
		$sql->query("INSERT INTO `flats` (`id`, `company_id`, `house_id`, `status`, 
					`flatnumber`) VALUES (:flat_id, 1, :house_id, :status, :number)");
		$sql->bind(':flat_id', $flat->id, PDO::PARAM_INT);
		$sql->bind(':house_id', $flat->house_id, PDO::PARAM_INT);
		$sql->bind(':status', $flat->status, PDO::PARAM_STR);
		$sql->bind(':number', $flat->number, PDO::PARAM_STR);
		$sql->execute('Не все параметры заданы правильно.');
		return $flat;
	}
	/**
	* Создает новую улицу.
	* @return object data_street
	*/
	public static function create_street(data_house $house, data_flat $flat){
		model_house::verify_id($house);
		model_flat::verify_id($house);

		exit();
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
		model_street::verify_company_id($street);
		model_street::verify_id($street);
		model_street::verify_city_id($street);
		model_street::verify_status($street);
		model_street::verify_name($street);
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
	* Возвращает следующий для вставки идентификатор дома.
	* @return int
	*/
	public static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_house_id` FROM `houses`");
		$sql->execute('Проблема при опредении следующего house_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего house_id.');
		$house_id = (int) $sql->row()['max_house_id'] + 1;
		$sql->close();
		return $house_id;
	}
	/**
	* Возвращает список домов
	* @return array из object data_house
	*/
	public static function get_houses(data_house $house){
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `city_id`, `street_id`, 
			 		`department_id`, `status`, `housenumber` as `number`
					FROM `houses`");
		if(!empty($house->id)){
			self::verify_id($house);
			$sql->query(' WHERE `id` = :house_id');
			$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_house(), 'Проблема при выборке домов из базы данных.');
	}
	/**
	* Возвращает лицевые счета дома.
	* @return array из object data_number
	*/
	public static function get_numbers(data_company $company, data_house $house){
		model_company::verify_id($company);
		self::verify_id($house);
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
					`streets`.`name` as `street_name`
				FROM `numbers`, `flats`, `houses`, `streets`
				WHERE `numbers`.`house_id` = :house_id
				AND `numbers`.`company_id` = :company_id
				AND `numbers`.`flat_id` = `flats`.`id`
				AND `numbers`.`house_id` = `houses`.`id`
				AND `houses`.`street_id` = `streets`.`id`");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		$sql->query(" ORDER BY (`flats`.`flatnumber` + 0)");
		return $sql->map(new data_number(), 'Проблемы при выборке номеров.');
	}
	/**
	* Возвращает лицевые счета дома.
	* @return array из object data_flat
	*/
	public static function get_flats(data_house $house, data_flat $flat){
		self::verify_id($house);
		$sql = new sql();
		$sql->query("SELECT `id`, `house_id`, `status`, `flatnumber` as `number`
					FROM `flats` WHERE `house_id` = :house_id");
		$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		if(!empty($flat->number)){
			model_flat::verify_number($flat);
			$sql->query(" AND `flatnumber` = :flat_number");
			$sql->bind(':flat_number', $flat->number, PDO::PARAM_INT);
		}
		$sql->query("ORDER BY (`flatnumber` + 0)");
		return $sql->map(new data_flat(), 'Проблемы при выборке квартир.');
	}

	/**
	* Проверка принадлежности объекта к классу data_house.
	*/
	public static function is_data_house($house){
		if(!($house instanceof data_house))
			throw new e_model('Возвращеный объект не является домом.');
	}
}