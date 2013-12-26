<?php
class mapper_query2work{

  private $company;
  private $query;

  private static $many = "SELECT `query2work`.`opentime` as `time_open`,
    `query2work`.`closetime` as `time_close`, `query2work`.`value`,
    `works`.`id`, `works`.`name`
    FROM `query2work`, `works`
    WHERE `query2work`.`company_id` = :company_id
    AND `works`.`company_id` = :company_id
    AND `works`.`id` = `query2work`.`work_id`
    AND `query2work`.`query_id` = :query_id";

  public static $indert = "INSERT INTO `query2work` (`query_id`, `work_id`, 
    `company_id`, `opentime`, `closetime`) VALUES (:query_id, :work_id,
    :company_id, :time_open, :time_close)";

  public static $delete = "DELETE FROM `query2work`
          WHERE `company_id` = :company_id AND `query_id` = :query_id
          AND `work_id` = :work_id";

  public function __construct($company, $query){
    $this->company = $company;
    $this->query = $query;
    data_company::verify_id($this->company->get_id());
    data_query::verify_id($this->query->get_id());
  }

  public function create_object(array $row){
    $factory = new factory_work();
    $work = $factory->create($row);
    $q2w = new data_query2work($work);
    $q2w->set_time_open($row['time_open']);
    $q2w->set_time_close($row['time_close']);
    $q2w->set_value($row['value']);
    return $q2w;
  }

  public function delete(data_query2work $work){
    $sql = new sql();
    $sql->query(self::$delete);
    $sql->bind(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':work_id', $work->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Ошибка при удалении работы из заявки.');
    return $query;
  }

  public function insert(data_query2work $work){
    $sql = new sql();
    $sql->query(self::$indert);
    $sql->bind(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':work_id', $work->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':time_open', $work->get_time_open(), PDO::PARAM_INT);
    $sql->bind(':time_close', $work->get_time_close(), PDO::PARAM_INT);
    $sql->execute('Ошибка при добавлении работы.');
    return $this->query;
  }

  private function get_works(){
    $sql = new sql();
    $sql->query(self::$many);
    $sql->bind(':query_id', (int) $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при запросе работ.');
    $stmt = $sql->get_stm();
    $works = [];
    while($row = $stmt->fetch())
      $works[] = $this->create_object($row);
    $stmt->closeCursor();
    return $works;
  }

  public function init_works(){
    $works = $this->get_works();
    if(!empty($works))
      foreach($works as $work)
        $this->query->add_work($work);
    return $this->query;
  }

  public function update_works(){
    $old = [];
    $works = $this->get_works();
    if(!empty($works))
      foreach($works as $work)
        $old[$work->get_id()] = $work;
    $new = $this->query->get_works();
    $deleted = array_diff_key($old, $new);
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
        foreach($inserted as $work)
            $this->insert($work);
    if(!empty($deleted))
        foreach($deleted as $work)
            $this->delete($work);
    return $this->query;
  }
}