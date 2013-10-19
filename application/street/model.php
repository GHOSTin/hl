<?php
class model_street{


	public static function create_street($city_id, $street_name){
		$city = (new model_city)->get_city($city_id);
		if(!is_null((new mapper_street)->get_street_by_name($street_name)))
			throw new e_model('Такая улица уже существует.');
		$mapper = new mapper_street();
		$street = new data_street();
		$street->set_id($mapper->get_insert_id());
		$street->set_name($street_name);
		$street->set_status('true');
		return $mapper->insert($city, $street);
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

	public static function get_streets(){
		return (new mapper_street)->get_streets();
	}
}