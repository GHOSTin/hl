<?php

class model_meter{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}

	public function get_meter($id){
		$meter = (new mapper_meter(di::get('pdo'), $this->company))->find($id);
		if(!($meter instanceof data_meter))
			throw new e_model('Счетчика не существует.');
		return $meter;
	}

	public function get_meters(){
		return (new mapper_meter(di::get('pdo'), $this->company))->get_meters();
	}

	public function get_meters_by_service($service){
	    return (new mapper_meter(di::get('pdo'), $this->company))->get_meters_by_service($service);
	}
}