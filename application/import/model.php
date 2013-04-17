<?php
class model_import{
	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public static function analize_import_numbers($fileArray){
		try{
			if(!file_exists($fileArray['tmp_name']))
				throw new e_model('Файла для обработки не существует.');
			$xml = simplexml_load_file($fileArray['tmp_name']);
			if($xml === false)
				throw new e_model('Ошибка в xml файле.');
			if(count($xml->flat) < 1)
				throw new e_model('Нечего импортировать.');
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
			if(count($houses) !== 1 OR !($houses[0] instanceof data_house))
				throw new e_model('Проблема при выборе дома.');
			$house = $houses[0];
			$i = 0;
			foreach($xml->flat as $flat_node)
				if(count($flat_node->number) > 0)
					foreach($flat_node as $number_node){
						$number_attr = $number_node->attributes();
						$new_number = new data_number();
						$new_number->fio = $number_attr->fio;
						$new_number->number = $number_attr->number;
						$numbers[] = $number;
						$number_values[] = $new_number->number;
						$number_params[] = ':number'.$i;
						$i++;
					}

			if(empty($numbers))
				throw new e_model('Нечего импортировать.');
			$sql = "SELECT `numbers`.`id`, `numbers`.`company_id`, 
					`numbers`.`city_id`, `numbers`.`house_id`, 
					`numbers`.`flat_id`, `numbers`.`number`,
					`numbers`.`type`, `numbers`.`status`,
					`numbers`.`fio`, `numbers`.`telephone`,
					`numbers`.`cellphone`, `numbers`.`password`,
					`numbers`.`contact-fio` as `contact_fio`,
					`numbers`.`contact-telephone` as `contact_telephone`,
					`numbers`.`contact-cellphone` as `contact_cellphone`
				FROM `numbers`
				WHERE `number` IN(".implode(',', $number_params).")";
			$stm = db::get_handler()->prepare($sql);
			foreach($number_values as $key => $value)
				$stm->bindParam(':number'.$key, $value, PDO::PARAM_STR);
			if($stm->execute() == false)
				throw new e_model('Проблема при выборке домов из базы данных.');
			$stm->setFetchMode(PDO::FETCH_CLASS, 'data_number');
			$old_numbers = [];
			while($number = $stm->fetch())
				$old_numbers[] = $number;
			$stm->closeCursor();
			var_dump($old_numbers);
			exit();
			return ['file' => $fileArray, 'house' => $house,
					'numbers' => $numbers ];
		}catch(exception $e){
			die($e->getMessage());
			if($e instanceof e_model)
				return ['error' => $e->getMessage()];
			else
				return ['error' => 'Неизвестная ошибка.'];
		}
	}
}