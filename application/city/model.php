<?php
class model_city{

	public function get_city($id){
		$city = di::get('mapper_city')->find($id);
		if(!($city instanceof data_city))
			throw new RuntimeException('Нет города.');
		return $city;
	}

	public function get_city_by_name($name){
		$city = di::get('mapper_city')->get_city_by_name($name);
		if(!($city instanceof data_city))
			throw new RuntimeException('Нет города.');
		return $city;
	}
}