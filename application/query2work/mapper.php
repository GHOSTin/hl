<?php
class mapper_query2work{

  private $company;
  private $query;

  private static $sql_get_works = "SELECT `query2work`.`opentime` as `time_open`,
    `query2work`.`closetime` as `time_close`, `query2work`.`value`,
    `works`.`id`, `works`.`name`
    FROM `query2work`, `works`
    WHERE `query2work`.`company_id` = :company_id
    AND `works`.`company_id` = :company_id
    AND `works`.`id` = `query2work`.`work_id`
    AND `query2work`.`query_id` = :query_id";

  public function __construct($company, $query){
    $this->company = $company;
    $this->query = $query;
    $this->company->verify('id');
    $this->query->verify('id');
  }

  public function create_object(array $row){
    $work = new data_work();
    $work->set_id($row['id']);
    $work->set_name($row['name']);
    $q2w = new data_query2work($work);
    $q2w->set_time_open($row['time_open']);
    $q2w->set_time_close($row['time_close']);
    $q2w->set_value($row['value']);
    return $q2w;
  }

  private function get_works(){
    $sql = new sql();
    $sql->query(self::$sql_get_works);
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
}