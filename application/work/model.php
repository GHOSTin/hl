<?php
class model_work{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		$this->company->verify('id');
	}
	
	/**
	* Возвращает список работ заявки
	* @return array из data_work
	*/
	public function get_work($id){
		return (new mapper_work($this->company))->find($id);
	}
}