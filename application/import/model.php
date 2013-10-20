<?php
class model_import{

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_flats($file){
		$xml = $this->get_xml_file($file['tmp_name']);
		$house_node = $xml->attributes();
		$city = (new model_city)->get_city_by_name((string) $house_node->city);
		$street = (new model_city2street($city))
			->get_street_by_name((string) $house_node->street);
		$house = (new model_street2house($street))
			->get_house_by_number((string) $house_node->number);
		$company = model_session::get_company();
		(new mapper_house2flat($company, $house))->init_flats();
		$flats = $old_flats = $new_flats = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$flats[] = $flat->get_number();
		foreach($xml->flat as $flat_node){
			$number = (string) $flat_node->attributes()->number;
			if(in_array($number, $flats))
				$old_flats[] = $number;
			else
				$new_flats[] = $number;
		}
		return ['file' => $file, 'city' => $city, 'street' => $street,
			'house' => $house, 'new_flats' => $new_flats, 'old_flats' => $old_flats];
	}

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_numbers($file){
		$xml = $this->get_xml_file($file['tmp_name']);
		$house_node = $xml->attributes();
		$city = (new model_city)->get_city_by_name((string) $house_node->city);
		$street = (new model_city2street($city))
			->get_street_by_name((string) $house_node->street);
		$house = (new model_street2house($street))
			->get_house_by_number((string) $house_node->number);
		(new mapper_house2flat(model_session::get_company(), $house))->init_flats();
		$old = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$old[$flat->get_number()] = $flat;
		$i = 0;
		$mapper = new mapper_house2number(model_session::get_company(), $house);
		foreach($xml->flat as $flat_node){
			$flat_number = (string) $flat_node->attributes()->number;
			if(!array_key_exists($flat_number, $old))
				throw new e_model('Не существует такой квартиры.');
			if(count($flat_node->number) > 0)
				foreach($flat_node as $number_node){
					$number_attr = $number_node->attributes();
					$num = (string) $number_attr->number;
					// старый номер
					$numbers[$num]['old'] = $mapper->get_number_by_number($num);
					// новый номер
					$new = new data_number();
					$new->set_fio((string) $number_attr->fio);
					$new->set_number($num);
					$new->set_flat($old[$flat_number]);
					$numbers[$num]['new'] = $new;
				}
		}
		return ['file' => $file, 'city' => $city, 'street' => $street,
			'house' => $house, 'numbers' => $numbers];
	}

	private function get_xml_file($file){
		if(!file_exists($file))
			throw new e_model('Файла для обработки не существует.');
		$xml = simplexml_load_file($file);
		if($xml === false)
			throw new e_model('Ошибка в xml файле.');
		return $xml;
	}

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_street($file){
		$xml = $this->get_xml_file($file['tmp_name']);
		$house_node = $xml->attributes();
		$city = (new model_city)->get_city_by_name((string) $house_node->city);
		$street = (new mapper_city2street($city))
			->get_street_by_name((string) $house_node->street);
		return ['file' => $file, 'city' => $city, 'street' => $street,
			'street_name' => (string) $house_node->street];
	}
	
	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_house($file){
		$xml = $this->get_xml_file($file['tmp_name']);
		$house_node = $xml->attributes();
		$city = (new model_city)->get_city_by_name((string) $house_node->city);
		$street = (new model_city2street($city))
			->get_street_by_name((string) $house_node->street);
		$house = (new mapper_street2house($street))
			->find_by_number((string) $house_node->number);
		return ['file' => $file, 'city' => $city, 'street' => $street,
		'house' => $house, 'house_number' => (string) $house_node->number];
	}

	public static function load_numbers(data_company $company, data_city $city_params,
						data_street $street_params, data_house $house_params, $numbers){
		if(empty($numbers))
			throw new e_model('Нечего импортировать.');
		$cities = model_city::get_cities($city_params);
		if(count($cities) !== 1)
			throw new e_model('Возвращено неверное количество городов.');
		$city = $cities[0];
		if(!($city instanceof data_city))
			throw new e_model('Проблема при запросе города.');
		$streets = model_street::get_streets($street_params);
		if(count($streets) !== 1)
			throw new e_model('Возвращено неверное количество улиц.');
		$street = $streets[0];
		if(!($street instanceof data_street))
			throw new e_model('Проблема при запросе улицы.');
		$houses = model_street::get_houses($street_params, $house_params);
		if(count($streets) !== 1)
			throw new e_model('Возвращено неверное количество домов.');
		$house = $houses[0];
		if(!($house instanceof data_house))
			throw new e_model('Проблема при запросе дома.');
		foreach($numbers as $number_data){
			$flat = new data_flat();
			$flat->number = $number_data['flat'];
			$flats = model_house::get_flats($house, $flat);
			if(count($flats) !== 1)
					throw new e_model('Квартира №'.$flat->number.' не существует в базе!');
			$flat = $flats[0];
			model_flat::is_data_flat($flat);
			$number = new data_number();
			$number->number = $number_data['number'];
			$number->fio = $number_data['fio'];
			$numbers = model_city::get_numbers($company, $city, $number);
			if(count($numbers) > 1){
				throw new e_model('Возвращается больше чем один лицевой счет.');
			}if(count($numbers) === 1){
				// лицевой счет существует нужно апдейтить
				$number = $numbers[0];
				model_number::is_data_number($number);
			}else{
				// лицевой счет отсутствует, нужно создавать
				model_city::create_number($company, $city, $street, $house, $flat, $number);
			}
		}
		return true;
	}

	public function load_flats(data_house $house, $flats){
		if(empty($flats))
			throw new e_model('Нечего импортировать.');
		$company = model_session::get_company();
		$mapper = new mapper_house2flat($company, $house);
		$mapper->init_flats();
		$old = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$old[] = $flat->get_number();
		foreach($flats as $flat_data){
			if(!in_array($flat_data, $old)){
				$flat = new data_flat();
				$flat->set_id($mapper->get_insert_id());
				$flat->set_number($flat_data);
				$flat->set_status('true');
				$mapper->insert($flat);
			}
		}
	}
}