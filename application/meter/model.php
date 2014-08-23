<?php

class model_meter{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}

	public function get_meter($id){
		$meter = di::get('mapper_meter')->find($id);
		if(!($meter instanceof data_meter))
			throw new RuntimeException('Счетчика не существует.');
		return $meter;
	}

	public function get_meters(){
		return di::get('mapper_meter')->get_meters();
	}

	public function get_meters_by_service($service){
	    return di::get('mapper_meter')->get_meters_by_service($service);
	}
}