<?php
class model_city{

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

	public function get_city($id){
		$city = (new mapper_city)->find($id);
		if(!($city instanceof data_city))
			throw new e_model('Нет города.');
		return $city;
	}

	public function get_city_by_name($name){
		$city = (new mapper_city)->get_city_by_name($name);
		if(!($city instanceof data_city))
			throw new e_model('Нет города.');
		return $city;
	}
}