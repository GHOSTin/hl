<?php
class model_work{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
	}
	
	public function get_work($id){
		return di::get('mapper_work')->find($id);
	}
}