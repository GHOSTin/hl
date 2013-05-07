<?php
class model_work{
	/**
	* Возвращает список работ заявки
	* @return array из data_work
	*/
	public static function get_works(data_work $work, data_current_user $user){
		model_user::verify_company_id($user);
		$sql = new sql();
		$sql->query("SELECT `id`,`company_id`, `status`, `name` FROM `works`
				WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $user->company_id, PDO::PARAM_INT);
		if(!empty($work->id)){}
			$sql->query(" AND `id` = :id");
			$sql->bind(':id', $work->id, PDO::PARAM_INT);
		}
		return $sql->map(new data_work(), 'Проблема при выборки работ.');
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