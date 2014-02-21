<?php
class mapper_work extends mapper{

	private $company;

	private static $find = "SELECT `id`,`company_id`, `status`, `name`
		FROM `works` WHERE `company_id` = :company_id  AND `id` = :id";

	public function __construct(PDO $pdo, data_company $company){
		parent::__construct($pdo);
		$this->company = $company;
	}

	public function find($id){
		$stmt = $this->pdo->prepare(self::$find);
		$stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		$stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new RuntimeException();
		$count = $stmt->rowCount();
		$factory = new factory_work();
		if($count === 0)
			return null;
		elseif($count === 1)
			return $factory->create($stmt->fetch());
		else
			throw new RuntimeException();
	}
}