<?php
class model_department{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

	public function get_department($id){
		$department = (new mapper_department($this->company))->find($id);
		if(!($department instanceof data_department))
			throw new e_model('Нет такого участка.');
		return $department;
	}

	public function get_departments(){
		return (new mapper_department($this->company))->get_departments();
	}
}