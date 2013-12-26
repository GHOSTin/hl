<?php
class mapper_work{

	private $company;
	private $pdo;

	private static $alert = "Проблема в мапере работ.";
	
	private static $sql_find = "SELECT `id`,`company_id`, `status`, `name`
		FROM `works` WHERE `company_id` = :company_id  AND `id` = :id";

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
		$this->pdo = di::get('pdo');
	}

	public function find($id){
		$stmt = $this->pdo->prepare(self::$sql_find);
		$stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		$stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new e_model(self::$alert);
		$count = $stmt->rowCount();
		$factory = new factory_work();
		if($count === 0)
			return null;
		elseif($count === 1)
			return $factory->create($stmt->fetch());
		else
			throw new e_model(self::$alert);
	}
}