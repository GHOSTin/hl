<?php
class model_work{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}
	
	public function get_work($id){
		return (new mapper_work($this->company))->find($id);
	}
}