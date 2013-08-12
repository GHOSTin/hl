<?php
class model_work{
	
	/**
	* Возвращает список работ заявки
	* @return array из data_work
	*/
	public static function get_works(data_company $company, data_work $work){
		$company->verify('id');
		$sql = new sql();
		$sql->query("SELECT `id`,`company_id`, `status`, `name` FROM `works`
					WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!empty($work->id)){
			$work->verify('id');
			$sql->query(" AND `id` = :id");
			$sql->bind(':id', $work->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_work(), 'Проблема при выборки работ.');
	}

	/**
	* Проверка принадлежности объекта к классу data_work.
	*/
	public static function is_data_work($work){
		if(!($work instanceof data_work))
			throw new e_model('Возвращеный объект не является работой.');
	}
}