<?php
class model_department{
	/**
	* Создает новый участок в компании.
	* @return object data_department
	*/
	public static function create_department(data_company $company, data_department $department, data_user $current_user){
		$department->company_id = $company->id;
		$department->id = self::get_insert_id($company);
		self::verify_department_id($department);
		self::verify_department_company_id($department);
		self::verify_department_status($department);
		self::verify_department_name($department);
		$sql = "INSERT INTO `departments` (`id`, `company_id`, `status`, `name`)
				VALUES (:department_id, :company_id, :status, :name);";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':department_id', $department->id);
		$stm->bindValue(':company_id', $department->company_id);
		$stm->bindValue(':status', $department->status);
		$stm->bindValue(':name', $department->name);
		if($stm->execute() == false)
			throw new e_model('Проблемы при создании нового участка.');
		$stm->closeCursor();
		return $department;
	}
	/**
	* Возвращает следующий для вставки идентификатор участка.
	* @return int
	*/
	private static function get_insert_id(data_company $company){
		model_company::verify_company_id($company);
		$sql = "SELECT MAX(`id`) as `max_department_id` FROM `departments`
				WHERE `company_id` = :company_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindValue(':company_id', $company->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблема при опредении следующего department_id.');
		if($stm->rowCount() !== 1)
			throw new e_model('Проблема при опредении следующего department_id.');
		$department_id = (int) $stm->fetch()['max_department_id'] + 1;
		$stm->closeCursor();
		return $department_id;
	}
	/**
	* Возвращает список участков компании.
	* @return array из object data_department
	*/
	public static function get_departments(data_department $department_params, data_user $current_user){
		$sql = "SELECT `id`, `company_id`, `status`, `name`
				FROM `departments` WHERE `departments`.`company_id` = :company_id";
		if($department_params->id > 0){
			$sql .= ' AND `id` IN(';
			if(is_array($department_params->id)){
				$count = count($department_params->id);
				$i = 1;
				foreach($department_params->id as $key => $department){
					$sql .= ':department_id'.$key;
					if($i++ < $count)
						$sql .= ',';
				}
			}else
				$sql .= ':department_id0';
			$sql .= ")";
		}
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if($department_params->id > 0)
			if(is_array($department_params->id))
				foreach($department_params->id as $key => $department)
					$stm->bindValue(':department_id'.$key, $department, PDO::PARAM_INT);
			else
				$stm->bindValue(':department_id0', $department_params->id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблемы при выборке участков.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_department');
		$result = [];
		while($department = $stm->fetch())
			$result[] = $department;
		$stm->closeCursor();
		return $result;
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