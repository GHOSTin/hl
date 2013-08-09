<?php
class model_import{

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public static function analize_import_flats($file_array){
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
			foreach($xml->flat as $flat_node){
				$flat = new data_flat();
				$flat->number = (string) $flat_node->attributes()->number;
				model_flat::verify_number($flat);
				$flats = model_house::get_flats($house, $flat);
				$flats_count = count($flats);
				if($flats_count === 1)
					$old_flats[] = $flats[0];
				elseif($flats_count === 0)
					$new_flats[] = $flat;
				else
					throw new e_model('Возврашено неверное количество квартир.');
			}
			return ['file' => $file_array, 'city' => $city, 'street' => $street, 'house' => $house,
					'old_flats' => $old_flats, 'new_flats' => $new_flats];
		}catch(exception $e){
			if($e instanceof e_model)
				throw new e_model($e->getMessage());
			else
				throw new e_model('Неизвестная ошибка!');
		}
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

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public static function analize_import_street($file_array){
		if(!file_exists($file_array['tmp_name']))
			throw new e_model('Файла для обработки не существует.');
		$xml = simplexml_load_file($file_array['tmp_name']);
		if($xml === false)
			throw new e_model('Ошибка в xml файле.');
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
		$count = count($streets);
		if($count === 0)
			$street = null;
		elseif($count === 1)
			if($streets[0] instanceof data_street)
				$street = $streets[0];
			else
				throw new e_model('Проблема при выборе улицы.');
		else
			throw new e_model('Проблема при выборе улицы.');
		return ['file' => $file_array, 'city' => $city, 'street' => $street, 'street_name' => (string) $house_node->street];
	}
	
	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public static function analize_import_house($file_array){
		if(!file_exists($file_array['tmp_name']))
			throw new e_model('Файла для обработки не существует.');
		$xml = simplexml_load_file($file_array['tmp_name']);
		if($xml === false)
			throw new e_model('Ошибка в xml файле.');
		$house_node = $xml->attributes();
		// проверка существования городв
		$city = new data_city();
		$city->name = (string) $house_node->city;
		$cities = model_city::get_cities($city);
		if(count($cities) !== 1 OR !($cities[0] instanceof data_city))
			throw new e_model('Проблема при выборе города.');
		$city = $cities[0];
		// проверка существования улицы
		$street = new data_street();
		$street->name = (string) $house_node->street;
		$streets = model_city::get_streets($city, $street);
		if(count($streets) !== 1 OR !($streets[0] instanceof data_street))
			throw new e_model('Проблема при выборе улицы.');
		$street = $streets[0];
		// проверка существования дома
		$house = new data_house();
		$house->number = (string) $house_node->number;
		$houses = model_street::get_houses($street, $house);
		$count = count($houses);
		if($count === 0)
			$house = null;
		elseif($count === 1)
			if($houses[0] instanceof data_house)
				$house = $houses[0];
			else
				throw new e_model('Проблема при выборе дома.');
		else
			throw new e_model('Проблема при выборе дома.');
		return ['file' => $file_array, 'city' => $city, 'street' => $street,
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

	public static function load_flats(data_city $city_params, data_street $street_params,
										data_house $house_params, $flats){
		if(empty($flats))
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
		foreach($flats as $flat_data){
			$flat = new data_flat();
			$flat->number = $flat_data;
			$flat->status = 'true';
			model_house::create_flat($house, $flat);
		}
	}
}