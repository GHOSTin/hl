<?php
class model_street{


	public static function create_street($city_id, $street_name){
		$city = (new model_city)->get_city($city_id);
		var_dump((new mapper_street)->get_street_by_name($street_name));
		exit();
		if(!is_null((new mapper_street)->get_street_by_name($street_name)))
			throw new e_model('Такая улица уже существует.');
		$mapper = new mapper_street();
		$street = new data_street();
		$street->set_id($mapper->get_insert_id());
		$street->set_name($street_name);
		$street->set_status('true');
		return $mapper->insert($city, $street);
	}

	/**
	* Создает новый дом.
	* @return object data_city
	*/
	public static function create_house(data_street $street, data_house $house){
		$house->verify('status', 'number');
		$street->verify('id');
		$street = model_street::get_streets($street)[0];
		model_street::is_data_street($street);
		$houses = model_street::get_houses($street, $house);
		if(count($houses) > 0)
			throw new e_model('Дом уже существует.');
		$house->company_id = $current_user->company_id;
		$house->city_id = $street->city_id;
		$house->street_id = $street->id;
		$house->id = model_house::get_insert_id();
		$sql = new sql();
		$sql->query("INSERT INTO `houses` (`id`, `company_id`, `city_id`, `street_id`,
					`department_id`, `status`, `housenumber`)
					VALUES (:house_id, 1, :city_id, :street_id, 1,
					:status, :number)");
		$sql->bind(':house_id', $house->id, PDO::PARAM_INT);
		$sql->bind(':city_id', $house->city_id, PDO::PARAM_INT);
		$sql->bind(':street_id', $house->street_id, PDO::PARAM_INT);
		$sql->bind(':status', $house->status, PDO::PARAM_STR);
		$sql->bind(':number', $house->number, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании нового дома.');
		return $house;
	}

	public static function get_street($id){
		$street = (new mapper_street)->find($id);
		if(!($street instanceof data_street))
			throw new e_model('Нет улицы');
		return $street;
	}

	public static function get_street_by_name($name){
		$street = (new mapper_street)->get_street_by_name($name);
		if(!($street instanceof data_street))
			throw new e_model('Нет улицы');
		return $street;
	}

	/**
	* Возвращает список улиц.
	* @return array из object data_street
	*/
	public static function get_streets(){
		return (new mapper_street)->get_streets();
	}
}