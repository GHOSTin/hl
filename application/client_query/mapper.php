<?php
class mapper_client_query extends mapper{

  private static $find = 'SELECT * FROM `client_queries`
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `time` = :time';

  private static $find_all = 'SELECT * FROM `client_queries`
    WHERE `company_id` = :company_id AND `status` = "new" ORDER BY `time` DESC';

  private static $update =  "UPDATE `client_queries`
    SET `company_id` = :company_id, `number_id` = :number_id, `time` = :time,
    `status` = :status, `text` = :text, `reason` = :reason, `query_id` = :query_id
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `time` = :time";

  public function find(data_company $company, $number_id, $time){
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number_id', $number_id, PDO::PARAM_INT);
    $stmt->bindValue(':time', $time, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return (new factory_client_query)->build($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function find_all_new(data_company $company){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new alert(self::$error);
    $queries = [];
    $factory = new factory_client_query();
    while($row = $stmt->fetch())
      $queries[] = $factory->build($row);
    return $queries;
  }

  public function update(data_client_query $query){
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':company_id', $query->get_company_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number_id', $query->get_number_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time', $query->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':status', $query->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':text', $query->get_text(), PDO::PARAM_STR);
    $stmt->bindValue(':reason', $query->get_reason(), PDO::PARAM_STR);
    $stmt->bindValue(':query_id', $query->get_query_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}