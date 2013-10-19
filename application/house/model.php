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
		$flat->verify('id', 'house_id', 'status', 'number');
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
		$house->verify('id');
		$flat->verify('id');
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

	public function get_house($id){
		$house = (new mapper_house())->find($id);
		if(!($house instanceof data_house))
			throw new e_model('Дом не существует.');
		return $house;
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
			die('DISABLED');
		}
		return $sql->map(new data_house(), 'Проблема при выборке домов из базы данных.');
	}
}