<?php
class mapper_query_work_type{

	private $company;
	private $pdo;

	private static $get_types = "SELECT `id`,`company_id`, `status`, `name`
		FROM `query_worktypes` WHERE `company_id` = :company_id";

	private static $find = "SELECT `id`,`company_id`, `status`, `name`
		FROM `query_worktypes` WHERE `company_id` = :company_id AND `id` = :id";

	public function __construct(data_company $company){
		$this->company = $company;
    $this->pdo = di::get('pdo');
	}

	public function create_object(array $row){
		$type = new data_query_work_type();
		$type->set_id($row['id']);
		$type->set_name($row['name']);
		return $type;
	}

	public  function find($id){
		$stmt = $this->pdo->prepare(self::$find);
		$stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
		$stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new RuntimeException();
		$count = $stmt->rowCount();
		if($count === 0)
			return null;
		elseif($count === 1)
			return $this->create_object($stmt->fetch());
		else
			throw new RuntimeException();
	}

	public  function get_query_work_types(){
		$stmt = $this->pdo->prepare(self::$get_types);
		$stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
		if(!$stmt->execute())
			throw new RuntimeException();
		$types = [];
		while($row = $stmt->fetch())
		  $types[] = $this->create_object($row);
		return $types;
	}
}