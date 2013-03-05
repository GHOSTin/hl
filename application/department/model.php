<?php
class model_department{
	public static function create_department(data_company $company, data_department $department, data_user $current_user){
		try{
			if(empty($department->status) OR empty($department->name))
				throw new exception('Не все параметры заданы правильно.');
			$department->company_id = $company->id;
			$department_id = self::get_insert_id($company);
			if($department_id === false)
				return false;
				$department->id = $department_id;
			$sql = "INSERT INTO `departments` (
						`id`, `company_id`, `status`, `name`
					) VALUES (
						:department_id, :company_id, :status, :name 
					);";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':department_id', $department->id);
			$stm->bindValue(':company_id', $department->company_id);
			$stm->bindValue(':status', $department->status);
			$stm->bindValue(':name', $department->name);
			if($stm->execute() === false)
				return false;
				return $department;
			$stm->closeCursor();
		}catch(exception $e){
			throw new exception('Проблемы при создании участка.');
		}
	}
	private static function get_insert_id(data_company $company){
		try{
			$sql = "SELECT MAX(`id`) as `max_department_id` FROM `departments`
				WHERE `company_id` = :company_id";
			$stm = db::get_handler()->prepare($sql);
			$stm->bindValue(':company_id', $company->id, PDO::PARAM_INT);
			$stm->execute();
			if($stm === false){
				return false;
			}else{
				if($stm->rowCount() === 1){
					return (int) $stm->fetch()['max_department_id'] + 1;
				}else
					return false;
			}
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего department_id.');
		}
	}			
}