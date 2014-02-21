<?php
class model_workgroup{

	private $company;

	public function __construct($company){
		$this->company = $company;
	}

	public function get_workgroups(){
		return di::get('mapper_workgroup')->get_workgroups();
	}
}