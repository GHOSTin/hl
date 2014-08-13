<?php
class model_department{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}

	public function get_department($id){
		$department = di::get('mapper_department')->find($id);
		if(!($department instanceof data_department))
			throw new e_model('Нет такого участка.');
		return $department;
	}

	public function get_departments(){
		return di::get('mapper_department')->get_departments();
	}
}