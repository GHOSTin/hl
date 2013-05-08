<?php
class model_department{
	/**
	* Создает новый участок в компании.
	* @return object data_department
	*/
	public static function create_department(data_company $company, data_department $department, data_current_user $current_user){
		$department->company_id = $company->id;
		$department->id = self::get_insert_id($company);
		self::verify_department_id($department);
		self::verify_department_company_id($department);
		self::verify_department_status($department);
		self::verify_department_name($department);
		$sql = new sql();
		$sql->query("INSERT INTO `departments` (`id`, `company_id`, `status`, `name`)
					VALUES (:department_id, :company_id, :status, :name)");
		$sql->bind(':department_id', $department->id, PDO::PARAM_INT);
		$sql->bind(':company_id', $department->company_id, PDO::PARAM_INT);
		$sql->bind(':status', $department->status, PDO::PARAM_STR);
		$sql->bind(':name', $department->name, PDO::PARAM_STR);
		$sql->execute('Проблемы при создании нового участка.');
		$$sql->close();
		return $department;
	}
	/**
	* Возвращает следующий для вставки идентификатор участка.
	* @return int
	*/
	private static function get_insert_id(data_company $company){
		model_company::verify_company_id($company);
		$sql = new sql();
		$sql->query("SELECT MAX(`id`) as `max_department_id` FROM `departments`
					WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		$sql->execute('Проблема при опредении следующего department_id.');
		if($sql->count() !== 1)
			throw new e_model('Проблема при опредении следующего department_id.');
		$department_id = (int) $sql->row()['max_department_id'] + 1;
		$sql->close();
		return $department_id;
	}
	/**
	* Возвращает список участков компании.
	* @return array из object data_department
	*/
	public static function get_departments(data_department $department_params, data_current_user $current_user){
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `status`, `name`
					FROM `departments` WHERE `departments`.`company_id` = :company_id");
		$sql->bind(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if(!empty($department_params->id)){
			$params = [];
			if(is_array($department_params->id))
				$departments = $department_params->id;
			else
				$departments[] = $department_params->id;
			foreach($departments as $key => $department){
				$params[] = ':department_id'.$key;
				$sql->bind(':department_id'.$key, $department, PDO::PARAM_INT);
			}
			$sql->query(" AND `id` IN(".implode(',', $params).")");
		}
		return $sql->map(new data_department(), 'Проблема при выборке пользователей.');
	}
	/**
	* Верификация идентификатора компании.
	*/
	public static function verify_company_id(data_department $department){
		if($department->company_id < 1)
			throw new e_model('Идентификатор компании задан не верно.');
	}
	/**
	* Верификация идентификатора участка.
	*/
	public static function verify_id(data_department $department){
		if($department->id < 1)
			throw new e_model('Идентификатор участка задан не верно.');
	}
	/**
	* Верификация названия участка.
	*/
	public static function verify_name(data_department $department){
		if(empty($department->name))
			throw new e_model('Название участка задано не верно.');
	}
	/**
	* Верификация статуса участка.
	*/
	public static function verify_status(data_department $department){
		if(empty($department->status))
			throw new e_model('Статус участка задан не верно.');
	}
	/**
	* Проверка принадлежности объекта к классу data_department.
	*/
	public static function is_data_department($department){
		if(!($department instanceof data_department))
			throw new e_model('Возвращеный объект не является участком.');
	}
}