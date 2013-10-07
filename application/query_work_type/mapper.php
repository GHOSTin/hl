<?php
class mapper_query_work_type{

	private $company;

	private static $sql_get_types = "SELECT `id`,`company_id`, `status`, `name`
		FROM `query_worktypes` WHERE `company_id` = :company_id";

	private static $sql_find = "SELECT `id`,`company_id`, `status`, `name`
		FROM `query_worktypes` WHERE `company_id` = :company_id AND `id` = :id";

	public function __construct(data_company $company){
		$this->company = $company;
    $this->company->verify('id');
	}

	public function create_object(array $row){
		$type = new data_query_work_type();
		$type->set_id($row['id']);
		$type->set_name($row['name']);
		return $type;
	}

	public  function find($id){
		$sql = new sql();
		$sql->query(self::$sql_find);
		$sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
		$sql->bind(':id', (int) $id, PDO::PARAM_INT);
		$sql->execute('Проблема при выборке типов заявки..');
		$stmt = $sql->get_stm();
		$count = $stmt->rowCount();
		if($count === 0)
			return null;
		elseif($count === 1)
			return $this->create_object($stmt->fetch());
		else
			throw new e_model('Неожиданное количество типов.');
	}
	
	/**
	* Возвращает список работ заявки
	* @return array из data_query_work_type
	*/
	public  function get_query_work_types(){
		$sql = new sql();
		$sql->query(self::$sql_get_types);
		$sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
		$sql->execute('Проблема при выборке типов заявки..');
		$stmt = $sql->get_stm();
		$types = [];
		while($row = $stmt->fetch())
		  $types[] = $this->create_object($row);
		return $types;
	}
}