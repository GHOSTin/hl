<?php
class model_city{

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