<?php
class model_workgroup{
	/**
	* Возвращает список групп работ
	* @return array из data_workgroup
	*/
	public static function get_workgroups(data_workgroup $workgroup_params, data_user $current_user){
		$sql = "SELECT `id`,`company_id`, `status`, `name`
				FROM `workgroups`
				WHERE `company_id` = :company_id";
				if(!empty($workgroup_params->id))
					$sql .= " AND `id` = :id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if(!empty($workgroup_params->id))
			$stm->bindValue(':id', $workgroup_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборки групп работ.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_workgroup');
		$result = [];
		while($works = $stm->fetch())
			$result[] = $works;
		$stm->closeCursor();
		return $result;
	}
	/**
	* Возвращает список работ группы
	* @return array из data_work
	*/
	public static function get_works(data_workgroup $workgroup_params, data_user $current_user){
		$sql = "SELECT `id`,`company_id`, `workgroup_id`, `status`, `name`
				FROM `works`
				WHERE `company_id` = :company_id";
				if(!empty($workgroup_params->id))
					$sql .= " AND `workgroup_id` = :workgroup_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if(!empty($workgroup_params->id))
			$stm->bindValue(':workgroup_id', $workgroup_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборки работ группы.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_work');
		$result = [];
		while($works = $stm->fetch())
			$result[] = $works;
		$stm->closeCursor();
		return $result;
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