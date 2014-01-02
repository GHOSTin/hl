<?php
class mapper_department{

	private $company;
	private $pdo;

	private static $alert = 'Проблема в мапере участка.';

	private static $many = "SELECT `id`, `company_id`, `status`, `name`
		FROM `departments` WHERE `company_id` = :company_id";

	private static $one = "SELECT `id`, `company_id`, `status`, `name`
		FROM `departments` WHERE `company_id` = :company_id AND `id` = :id";

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
		$this->pdo = di::get('pdo');
	}

	public function create_object(array $row){
		$department = new data_department();
		$department->set_id($row['id']);
		$department->set_name($row['name']);
		$department->set_status($row['status']);
		return $department;
	}

	public function find($id){
		$stmt = $this->pdo->prepare(self::$one);
		$stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		$stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new e_model(self::$alert);
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
	* @return array
	*/
	public function get_departments(){
		$stmt = $this->pdo->prepare(self::$many);
		$stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new e_model(self::$alert);
		$departments = [];
		while($row = $stmt->fetch())
			$departments[] = $this->create_object($row);
		return $departments;
	}
}