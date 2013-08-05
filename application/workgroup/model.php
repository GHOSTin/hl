<?php
class model_workgroup{
	/**
	* Возвращает список групп работ
	* @return array из data_workgroup
	*/
	public static function get_workgroups(data_company $company, data_workgroup $workgroup){
		$company->verify('id');
		$sql = new sql();
		$sql->query("SELECT `id`,`company_id`, `status`, `name` FROM `workgroups`
					WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!empty($workgroup->id)){
			$workgroup->verify('id');
			$sql->query(" AND `id` = :id");
			$sql->bind(':id', $workgroup->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_workgroup(), 'Проблема при выборке групп работ.');
	}
	/**
	* Возвращает список работ группы
	* @return array из data_work
	*/
	public static function get_works(data_company $company, data_workgroup $workgroup){
		$company->verify('id');
		$sql = new sql();
		$sql->query("SELECT `id`,`company_id`, `workgroup_id`, `status`, `name`
					FROM `works` WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!empty($workgroup->id)){
			$workgroup->verify('id');
			$sql->query(" AND `workgroup_id` = :workgroup_id");
			$sql->bind(':workgroup_id', $workgroup->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_work(), 'Проблема при выборки работ группы.');
	}

	/**
	* Проверка принадлежности объекта к классу data_workgroup
	*/
	public static function is_data_work($workgroup){
		if(!($workgroup instanceof data_workgroup))
			throw new e_model('Возвращеный объект не является группой работ.');
	}
}