<?php
class mapper_department{

	private $company;

	private static $many = "SELECT `id`, `company_id`, `status`, `name`
		FROM `departments` WHERE `company_id` = :company_id";

	private static $one = "SELECT `id`, `company_id`, `status`, `name`
		FROM `departments` WHERE `company_id` = :company_id AND `id` = :id";

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

	public function create_object(array $row){
		$department = new data_department();
		$department->set_id($row['id']);
		$department->set_name($row['name']);
		return $department;
	}

	public function find($id){
		$sql = new sql();
		$sql->query(self::$one);
		$sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		$sql->bind(':id', (int) $id, PDO::PARAM_INT);
		$sql->execute('Проблема при выборке участка.');
		$stmt = $sql->get_stm();
		$count = $stmt->rowCount();
		if($count === 0)
			return null;
		elseif($count === 1)
			return $this->create_object($stmt->fetch());
		else
			throw new e_model('Неожиданное количество записей');
	}

	/**
	* Возвращает список участков компании.
	* @return array из object data_department
	*/
	public function get_departments(){
		$sql = new sql();
		$sql->query(self::$many);
		$sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		$sql->execute('Проблема при выборке пользователей.');
		$stmt = $sql->get_stm();
		$departments = [];
		while($row = $stmt->fetch())
			$departments[] = $this->create_object($row);
		return $departments;
	}
}