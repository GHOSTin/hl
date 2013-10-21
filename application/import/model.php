<?php
class model_import{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		$this->company->verify('id');
	}

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
		(new mapper_house2flat($this->company, $house))->init_flats();
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
		(new mapper_house2flat($this->company, $house))->init_flats();
		$old = [];
		if(!empty($house->get_flats()))
			foreach($house->get_flats() as $flat)
				$old[$flat->get_number()] = $flat;
		$i = 0;
		$mapper = new mapper_house2number($this->company, $house);
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

	public function load_numbers($city_id, $street_id, $house_id, $numbers){
		if(empty($numbers))
			throw new e_model('Нечего импортировать.');
		$city = new data_city($city_id);
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
				$number->set_number($number_data['number']);
				$number->set_fio($number_data['fio']);
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
				$flat->set_number($flat_data);
				$flat->set_status('true');
				$mapper->insert($flat);
			}
		}
	}
}