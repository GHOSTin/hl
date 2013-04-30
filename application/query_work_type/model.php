<?php
class model_query_work_type{
	/**
	* Возвращает список работ заявки
	* @return array из data_query_work_type
	*/
	public static function get_query_work_types(data_query_work_type $query_work_type_params, data_user $current_user){
		$sql = "SELECT `id`,`company_id`, `status`, `name` FROM `query_worktypes`
				WHERE `company_id` = :company_id";
				if(!empty($query_work_type_params->id))
					$sql .= " AND `id` = :id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if(!empty($query_work_type_params->id))
			$stm->bindValue(':id', $query_work_type_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборки типов заявки.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_query_work_type');
		$result = [];
		while($query_work_type = $stm->fetch())
			$result[] = $query_work_type;
		$stm->closeCursor();
		return $result;
	}
}