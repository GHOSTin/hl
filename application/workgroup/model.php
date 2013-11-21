<?php
class model_workgroup{

	private $company;

	public function __construct($company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

	/**
	* Возвращает список групп работ
	* @return array из data_workgroup
	*/
	public function get_workgroups(){
		return (new mapper_workgroup($this->company))->get_workgroups();
	}
}