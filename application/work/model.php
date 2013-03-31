<?php
class model_work{
	/**
	* Возвращает список работ заявки
	* @return array из data_work
	*/
	public static function get_works(data_work $work_params, data_user $current_user){
		$sql = "SELECT `id`,`company_id`, `status`, `name`
				FROM `works`
				WHERE `company_id` = :company_id";
				if(!empty($work_params->id))
					$sql .= " AND `id` = :id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if(!empty($work_params->id))
			$stm->bindValue(':id', $work_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при выборки работ.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_work');
		$result = [];
		while($works = $stm->fetch())
			$result[] = $works;
		$stm->closeCursor();
		return $result;
	}
}