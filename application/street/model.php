<?php
class model_street{

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

	/**
	* Возвращает следующий для вставки идентификатор улицы.
	* @return int
	*/
	public static function get_insert_id(){
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_street_id` FROM `streets`");
		$sql->execute('Проблема при опредении следующего street_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего street_id.');
		$street_id = (int) $sql->row()['max_street_id'] + 1;
		$sql->close();
		return $street_id;
	}

	public static function get_street($id){
		$street = (new mapper_street)->find($id);
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