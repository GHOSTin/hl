<?php
class model_import{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_flats($file){
		$import = new data_import($file['tmp_name']);
		$street = (new mapper_city2street($import->get_city()))
			->get_street_by_name($import->get_street()->get_name());
		$house = (new mapper_street2house($street))
			->find_by_number($import->get_house()->get_number());
		(new mapper_house2flat($this->company, $house))->init_flats();
		$old_flats = $new_flats = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$old_flats[] = $flat->get_number();
		return ['import' => $import, 'street' => $street, 'house' => $house,
			'new_flats' => array_diff($import->get_flats(), $old_flats),
			'old_flats' => $old_flats];
	}

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_numbers($file){
		$import = new data_import($file['tmp_name']);
		$xml = $import->get_xml();
		$house_node = $xml->attributes();
		$city = (new model_city)->get_city_by_name((string) $house_node->city);
		$street = (new model_city2street($city))
			->get_street_by_name((string) $house_node->street);
		$house = (new model_street2house($street))
			->get_house_by_number((string) $house_node->number);
		(new mapper_house2flat($this->company, $house))->init_flats();
		$old = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$old[$flat->get_number()] = $flat;
		$i = 0;
		$mapper = new mapper_house2number($this->company, $house);
		foreach($xml->flat as $flat_node){
			$flat_number = trim((string) $flat_node->attributes()->number);
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

	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_meters($file){
		$import = new data_import(ROOT.'/specifications/import_numbers.xml');

		var_dump($import->get_street());
		var_dump($import->get_city());
		var_dump($import->get_house());

		exit();
		$house_node = $xml->attributes();
		$city = (new model_city)->get_city_by_name((string) $house_node->city);
		$street = (new model_city2street($city))
			->get_street_by_name((string) $house_node->street);
		$house = (new model_street2house($street))
			->get_house_by_number((string) $house_node->number);
		(new model_house2number(di::get('company'), $house))->init_numbers();
		if(!empty($house->get_numbers))
		return ['file' => $file, 'city' => $city, 'street' => $street,
			'house' => $house, 'numbers' => $numbers];
	}
 
	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_street($file){
		$import = new data_import($file['tmp_name']);
		$city = $import->get_city();
		$street = (new mapper_city2street($city))
			->get_street_by_name((string) $import->get_street()->get_name());
		return ['import' => $import, 'street' => $street, 'city' => $city];
	}
	
	/*
	* Анализирует файт импорта лицевых счетов
	*/
	public function analize_import_house($file){
		$import = new data_import($file['tmp_name']);
		$street = (new mapper_city2street($import->get_city()))
			->get_street_by_name($import->get_street()->get_name());
		$house = (new mapper_street2house($street))
			->find_by_number($import->get_house()->get_number());
		return ['import' => $import, 'street' => $street, 'house' => $house];
	}

	public function load_numbers($city_id, $street_id, $house_id, $numbers){
		if(empty($numbers))
			throw new e_model('Нечего импортировать.');
		$city = new data_city();
		$city->set_id($city_id);
		$street = (new model_city2street($city))
		  ->get_street($street_id);
		$house = (new model_street2house($street))
		  ->get_house($house_id);
		(new mapper_house2flat($this->company, $house))->init_flats();
		$old = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$old[$flat->get_number()] = $flat;
		foreach($numbers as $number_data){
			if(!array_key_exists($number_data['flat'], $old))
				throw new e_model('Квартиры не существует.');
			$number = (new mapper_house2number($this->company, $house))
				->get_number_by_number($number_data['number']);
			$mapper = new mapper_house2number($this->company, $house);
			if($number instanceof data_number)
				$n = null;
			else{
				$number = new data_number();
				$number->set_id($mapper->get_insert_id());
				$number->set_number(trim($number_data['number']));
				$number->set_fio(trim($number_data['fio']));
				$number->set_flat($old[$number_data['flat']]);
				$number->set_status('true');
				$mapper->insert($number);
			}
		}
		return true;
	}

	public function load_flats(data_house $house, $flats){
		if(empty($flats))
			throw new e_model('Нечего импортировать.');
		$mapper = new mapper_house2flat($this->company, $house);
		$mapper->init_flats();
		$old = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$old[] = $flat->get_number();
		foreach($flats as $flat_data){
			if(!in_array($flat_data, $old)){
				$flat = new data_flat();
				$flat->set_id($mapper->get_insert_id());
				$flat->set_number(trim($flat_data));
				$flat->set_status('true');
				$mapper->insert($flat);
			}
		}
	}
}