<?php
class model_work{
	/**
	* Возвращает список работ заявки
	* @return array из data_work
	*/
	public static function get_works(data_work $work_params, data_current_user $current_user){
		$sql = "SELECT `id`,`company_id`, `status`, `name` FROM `works`
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
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_work $work){
		if($work->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора работы.
	*/
	public static function verify_id(data_work $work){
		if($work->id < 1)
			throw new e_model('Идентификатор работы задан не верно.');
	}
	/**
	* Верификация названия работы.
	*/
	public static function verify_name(data_work $work){
		if(empty($work->name))
			throw new e_model('Название работы задан не верно.');
	}
	/**
	* Верификация статуса работы.
	*/
	public static function verify_status(data_work $work){
		if(empty($work->status))
			throw new e_model('Статус работы задан не верно.');
	}
	/**
	* Верификация идентификатора группы работы.
	*/
	public static function verify_workgroup_id(data_work $work){
		if(empty($work->workgroup_id))
			throw new e_model('Идентификатор группы работы задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_work.
	*/
	public static function is_data_work($work){
		if(!($work instanceof data_work))
			throw new e_model('Возвращеный объект не является работой.');
	}
}