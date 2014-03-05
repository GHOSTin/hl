<?php
class mapper_department extends mapper{

	private $company;

	private static $many = "SELECT `id`, `company_id`, `status`, `name`
		FROM `departments` WHERE `company_id` = :company_id";

	private static $one = "SELECT `id`, `company_id`, `status`, `name`
		FROM `departments` WHERE `company_id` = :company_id AND `id` = :id";

	public function __construct(PDO $pdo, data_company $company){
		parent::__construct($pdo);
		$this->company = $company;
	}

	public function find($id){
		$stmt = $this->pdo->prepare(self::$one);
		$stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		$stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new RuntimeException();
		$count = $stmt->rowCount();
		if($count === 0)
			return null;
		elseif($count === 1)
			return di::get('factory_department')->build($stmt->fetch());
		else
			throw new RuntimeException();
	}

	public function get_departments(){
		$stmt = $this->pdo->prepare(self::$many);
		$stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new RuntimeException();
		$departments = [];
		$factory = di::get('factory_department');
		while($row = $stmt->fetch())
			$departments[] = $factory->build($row);
		return $departments;
	}
}