<?php
class mapper_workgroup{

	private $company;

	private static $many = "SELECT `id`,`company_id`, `status`,
		`name` FROM `workgroups` WHERE `company_id` = :company_id";

	public function __construct($company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

	public function create_object(array $row){
		$group = new data_workgroup();
		$group->set_id($row['id']);
		$group->set_name($row['name']);
		$group->set_status($row['status']);
		return $group;
	}

	/**
	* Возвращает список групп работ
	* @return array
	*/
	public function get_workgroups(){
		$sql = new sql();
		$sql->query(self::$many);
		$sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
		$sql->execute('Проблема при выборке групп работ.');
		$stmt = $sql->get_stm();
		$groups = [];
		while($row = $stmt->fetch())
			$groups[] = $this->create_object($row);
		return $groups;
	}
}