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
	public static function analize_import_numbers($file_array){
		try{
			if(!file_exists($file_array['tmp_name']))
				throw new e_model('Файла для обработки не существует.');
			$xml = simplexml_load_file($file_array['tmp_name']);
			if($xml === false)
				throw new e_model('Ошибка в xml файле.');
			if(count($xml->flat) < 1)
				throw new e_model('Нечего импортировать.');
			$house_node = $xml->attributes();
			// проверка существования городв
			$city = new data_city();
			$city->name = (string) $house_node->city;
			model_city::verify_name($city);
			$cities = model_city::get_cities($city);
			if(count($cities) !== 1 OR !($cities[0] instanceof data_city))
				throw new e_model('Проблема при выборе города.');
			$city = $cities[0];
			// проверка существования улицы
			$street = new data_street();
			$street->name = (string) $house_node->street;
			model_street::verify_name($street);
			$streets = model_city::get_streets($city, $street);
			if(count($streets) !== 1 OR !($streets[0] instanceof data_street))
				throw new e_model('Проблема при выборе улицы.');
			$street = $streets[0];
			// проверка существования дома
			$house = new data_house();
			$house->number = (string) $house_node->number;
			$houses = model_street::get_houses($street, $house);
			if(count($houses) !== 1 OR !($houses[0] instanceof data_house))
				throw new e_model('Проблема при выборе дома.');
			$house = $houses[0];
			$i = 0;
			foreach($xml->flat as $flat_node){
				$flat = new data_flat();
				$flat->number = (string) $flat_node->attributes()->number;
				model_flat::verify_number($flat);
				if(count(model_house::get_flats($house, $flat)) !== 1)
					throw new e_model('Квартира №'.$flat->number.' не существует в базе!');
				if(count($flat_node->number) > 0)
					foreach($flat_node as $number_node){
						$number_attr = $number_node->attributes();
						$number = new data_number();
						$number->fio = (string) $number_attr->fio;
						$number->number = (string) $number_attr->number;
						$number->flat_number = $flat->number;
						if(isset($numbers[$number->number]))
							throw new e_model('В xml файле повторются номера лицевых счетов.');
						$numbers[$number->number]['new'] = $number;
						$number_values[] = $number->number;
						$number_params[] = ':number'.$i;
						$i++;
					}
			}
			if(empty($numbers))
				throw new e_model('Нечего импортировать.');
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
					`flats`.`flatnumber` as `flat_number`
					FROM `numbers`, `flats` WHERE `numbers`.`number` IN(".implode(',', $number_params).")
					AND `numbers`.`flat_id` = `flats`.`id`");
			foreach($number_values as $key => $value)
				$sql->bind(':number'.$key, $value, PDO::PARAM_STR);
			$old_numbers = $sql->map(new data_number, 'Проблема при выборке домов из базы данных.');
			$sql->close();
			foreach($old_numbers as $number)
				$numbers[$number->number]['old'] = $number;
			return ['file' => $file_array, 'city' => $city, 'street' => $street, 'house' => $house,
					'numbers' => $numbers];
		}catch(exception $e){
			die($e->getMessage());
			if($e instanceof e_model)
				return ['error' => $e->getMessage()];
			else
				return ['error' => 'Неизвестная ошибка.'];
		}
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
				$old[] = $flat->get_id();
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