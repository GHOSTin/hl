<?php
class model_department{

	/**
	* Создает новый участок в компании.
	* @return object data_department
	*/
	public static function create_department(data_company $company, data_department $department,
											data_current_user $user){
		model_company::verify_id($company);
		$department->company_id = $company->id;
		$department->id = self::get_insert_id($company);
		$department->verify('id'. 'company_id', 'status', 'name');
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
		model_company::verify_id($company);
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
	public static function get_departments(data_company $company, data_department $department){
		model_company::verify_id($company);
		$sql = new sql();
		$sql->query("SELECT `id`, `company_id`, `status`, `name`
					FROM `departments` WHERE `company_id` = :company_id");
		$sql->bind(':company_id', $company->id, PDO::PARAM_INT);
		if(!empty($department->id)){
			$params = [];
			if(is_array($department->id))
				$departments = $department->id;
			else
				$departments[] = $department->id;
			foreach($departments as $key => $department){
				$params[] = ':department_id'.$key;
				$sql->bind(':department_id'.$key, $department, PDO::PARAM_INT);
			}
			$sql->query(" AND `id` IN(".implode(',', $params).")");
		}
		return $sql->map(new data_department(), 'Проблема при выборке пользователей.');
	}

	/**
	* Проверка принадлежности объекта к классу data_department.
	*/
	public static function is_data_department($department){
		if(!($department instanceof data_department))
			throw new e_model('Возвращеный объект не является участком.');
	}
}