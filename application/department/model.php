<?php
class model_department{

	private $company;

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

	/**
	* Создает новый участок в компании.
	* @return object data_department
	*/
	public static function create_department(data_company $company,
													data_department $department, data_current_user $user){
		$company->verify('id');
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
		$company->verify('id');
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

	public function get_department($id){
		$department = (new mapper_department($this->company))->find($id);
		if(!($department instanceof data_department))
			throw new e_model('Нет такого участка.');
		return $department;
	}
	
	/**
	* Возвращает список участков компании.
	* @return array из object data_department
	*/
	public function get_departments(){
		return (new mapper_department($this->company))->get_departments();
	}
}