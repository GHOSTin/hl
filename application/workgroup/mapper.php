<?php
class mapper_workgroup extends mapper{

	private $company;

	private static $many = "SELECT `id`,`company_id`, `status`,
		`name` FROM `workgroups` WHERE `company_id` = :company_id";

	public function __construct(PDO $pdo, data_company $company){
		parent::__construct($pdo);
		$this->company = $company;
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