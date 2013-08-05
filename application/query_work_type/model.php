<?php
class model_query_work_type{
	/**
	* Возвращает список работ заявки
	* @return array из data_query_work_type
	*/
	public static function get_query_work_types(data_company $company, data_query_work_type $query_work_type_params){
		$company->verify('id');
		$sql = new sql();
		$sql->query("SELECT `id`,`company_id`, `status`, `name` FROM `query_worktypes`
					WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!empty($query_work_type_params->id)){
			$query_work_type_params->verify('id');
			$sql->query(" AND `id` = :id");
			$sql->bind(':id', $query_work_type_params->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_query_work_type(), 'Проблема при выборке типов заявки.');
	}

	/**
	* Проверка принадлежности объекта к классу data_query_work_type.
	*/
	public static function is_data_query_work_type($query_work_type){
		if(!($query_work_type instanceof data_query_work_type))
			throw new e_model('Возвращеный объект не является типом работ заявки.');
	}
}