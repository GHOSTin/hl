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
}