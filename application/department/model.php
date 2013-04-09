<?php
class model_department{
	/**
	* Создает новый участок в компании.
	* @return object data_department
	*/
	public static function create_department(data_company $company, data_department $department, data_user $current_user){
		if(empty($department->status) OR empty($department->name))
			throw new e_params('status и name заданы не правильно.');
		$department->company_id = $company->id;
		$department->id = self::get_insert_id($company);
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
	public static function get_departments(data_user $current_user){
		$sql = "SELECT `id`, `company_id`, `status`, `name`
				FROM `departments` WHERE `departments`.`company_id` = :company_id";
		$stm = db::get_handler()->prepare($sql);
		$stm->bindParam(':company_id', $current_user->company_id, PDO::PARAM_INT);
		if($stm->execute() == false)
			throw new e_model('Проблемы при выборке участков.');
		$stm->setFetchMode(PDO::FETCH_CLASS, 'data_department');
		$result = [];
		while($department = $stm->fetch())
			$result[] = $department;
		$stm->closeCursor();
		return $result;
	}
}