<?php
class model_workgroup{
	/**
	* Возвращает список групп работ
	* @return array из data_workgroup
	*/
	public static function get_workgroups(data_company $company, data_workgroup $workgroup){
		model_user::verify_company_id($user);
		$sql = new sql();
		$sql->query("SELECT `id`,`company_id`, `status`, `name` FROM `workgroups`
				WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $user->company_id, PDO::PARAM_INT);
		if(!empty($workgroup->id)){
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
		$sql = new sql();
		$sql->query("SELECT `id`,`company_id`, `workgroup_id`, `status`, `name`
					FROM `works` WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $user->company_id, PDO::PARAM_INT);
		if(!empty($workgroup->id)){
			$sql->query(" AND `workgroup_id` = :workgroup_id");
			$sql->bind(':workgroup_id', $workgroup->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_work(), 'Проблема при выборки работ группы.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_workgroup $workgroup){
		if($workgroup->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора группы работ.
	*/
	public static function verify_id(data_workgroup $workgroup){
		if($workgroup->id < 1)
			throw new e_model('Идентификатор группы работ задан не верно.');
	}
	/**
	* Верификация названия группы работ.
	*/
	public static function verify_name(data_workgroup $workgroup){
		if(empty($workgroup->name))
			throw new e_model('Название группы работ задано не верно.');
	}
	/**
	* Верификация статуса группы работ.
	*/
	public static function verify_status(data_workgroup $workgroup){
		if(empty($workgroup->status))
			throw new e_model('Статус группы работ задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_workgroup
	*/
	public static function is_data_work($workgroup){
		if(!($workgroup instanceof data_workgroup))
			throw new e_model('Возвращеный объект не является группой работ.');
	}
}