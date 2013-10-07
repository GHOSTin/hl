<?php
class model_query_work_type{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
    $this->company->verify('id');
	}


	public function get_query_work_type($id){
		$type = (new mapper_query_work_type($this->company))->find($id);
		if(!($type instanceof data_query_work_type))
			throw new e_model('Нет такого типа работ.');
		return $type;
	}
	
	/**
	* Возвращает список работ заявки
	* @return array из data_query_work_type
	*/
	public function get_query_work_types(){
		return (new mapper_query_work_type($this->company))->get_query_work_types();
	}
}