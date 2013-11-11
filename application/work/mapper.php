<?php
class mapper_work{

	private $company;
	
	private static $sql_find = "SELECT `id`,`company_id`, `status`, `name`
		FROM `works` WHERE `company_id` = :company_id  AND `id` = :id";

	public function __construct(data_company $company){
		$this->company = $company;
		data_company::verify_id($this->company->get_id());
	}

	public function create_object(array $row){
		$work = new data_work();
		$work->set_id($row['id']);
		$work->set_name($row['name']);
		return $work;
	}
	
	/**
	* Возвращает список работ заявки
	* @return array из data_work
	*/
	public function find($id){
		$sql = new sql();
		$sql->query(self::$sql_find);
		$sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
		$sql->bind(':id', (int) $id, PDO::PARAM_INT);
		$sql->execute('Проблема при выборки работ.');
		$stmt = $sql->get_stm();
		$count = $stmt->rowCount();
		if($count === 0)
			return null;
		elseif($count === 1)
			return $this->create_object($stmt->fetch());
		else
			throw new e_model('Неожиданное количество записей.');
	}
}