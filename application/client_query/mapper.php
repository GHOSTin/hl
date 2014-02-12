<?php
class mapper_client_query{

  private $pdo;
  private $company;

  private static $find_all = 'SELECT * FROM `client_queries`
    WHERE `company_id` = :company_id AND `status` = "new" ORDER BY `time` DESC';

  private static $insert =  "INSERT INTO `client_queries` (
    `company_id`, `number_id`, `time`, `status`, `text`, `reason`, `query_id`)
    VALUES (:company_id, :number_id, :time, :status, :text, :reason, :query_id)";

  public function __construct(PDO $pdo, data_company $company){
    $this->pdo = $pdo;
    $this->company = $company;
  }

  public function find_all_new(){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new alert(self::$error);
    $queries = [];
    $factory = new factory_client_query();
    while($row = $stmt->fetch())
      $queries[] = $factory->build($row);
    return $queries;
  }

  // public function insert(client_query $query){
  //   $stmt = $this->pdo->prepare(self::$insert);
  //   $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
  //   $stmt->bindValue(':number_id', $this->number->get_id(), PDO::PARAM_INT);
  //   $stmt->bindValue(':time', $query->get_time(), PDO::PARAM_INT);
  //   $stmt->bindValue(':status', $query->get_status(), PDO::PARAM_STR);
  //   $stmt->bindValue(':text', $query->get_text(), PDO::PARAM_STR);
  //   $stmt->bindValue(':reason', $query->get_reason(), PDO::PARAM_STR);
  //   $stmt->bindValue(':query_id', $query->get_query_id(), PDO::PARAM_INT);
  //   if(!$stmt->execute())
  //     throw new alert(self::$error);
  //   return $query;
  // }
}