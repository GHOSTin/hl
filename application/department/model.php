<?php
class model_department{
	public static function create_department(data_company $company, data_department $department, data_user $current_user){
		try{
			if(empty($department->status) OR empty($department->name))
				throw new exception('Не все параметры заданы правильно.');
			$department->company_id = $company->id;
			$department->id = self::get_insert_id($company);
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
			if($stm->execute() == false)
				throw new exception('Проблемы при создании участка.');
			$stm->closeCursor();
			return $department;
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
			if($stm->execute() == false)
				throw new exception('Проблема при опредении следующего department_id.');
				if($stm->rowCount() !== 1)
					throw new exception('Проблема при опредении следующего department_id.');
			$department_id = (int) $stm->fetch()['max_department_id'] + 1;
			$stm->closeCursor();
			return $department_id;
		}catch(exception $e){
			throw new exception('Проблема при опредении следующего department_id.');
		}
	}			
}