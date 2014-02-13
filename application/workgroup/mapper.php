<?php
class mapper_workgroup{

	private $company;
	private $pdo;

	private static $many = "SELECT `id`,`company_id`, `status`,
		`name` FROM `workgroups` WHERE `company_id` = :company_id";

	public function __construct($company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
		$this->pdo = di::get('pdo');
	}

	public function create_object(array $row){
		$group = new data_workgroup();
		$group->set_id($row['id']);
		$group->set_name($row['name']);
		$group->set_status($row['status']);
		return $group;
	}

	public function get_workgroups(){
		$stmt = $this->pdo->prepare(self::$many);
		$stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new RuntimeException();
		$groups = [];
		while($row = $stmt->fetch())
			$groups[] = $this->create_object($row);
		return $groups;
	}
}