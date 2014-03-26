<?php
class mapper_query2work extends mapper{

  private static $many = "SELECT `query2work`.`opentime` as `time_open`,
    `query2work`.`closetime` as `time_close`, `query2work`.`value`,
    `works`.`id`, `works`.`name`
    FROM `query2work`, `works`
    WHERE `query2work`.`company_id` = :company_id
    AND `works`.`company_id` = :company_id
    AND `works`.`id` = `query2work`.`work_id`
    AND `query2work`.`query_id` = :query_id";

  public static $insert = "INSERT INTO `query2work` (`query_id`, `work_id`,
    `company_id`, `opentime`, `closetime`) VALUES (:query_id, :work_id,
    :company_id, :time_open, :time_close)";

  public static $delete = "DELETE FROM `query2work`
          WHERE `company_id` = :company_id AND `query_id` = :query_id
          AND `work_id` = :work_id";

  public function create_object(array $row){
    $work = di::get('factory_work')->create($row);
    $q2w = new data_query2work($work);
    $q2w->set_time_open($row['time_open']);
    $q2w->set_time_close($row['time_close']);
    $q2w->set_value($row['value']);
    return $q2w;
  }

  public function delete(data_company $company, data_query $query,
                          data_query2work $work){
    $stmt = $this->pdo->prepare(self::$delete);
    $stmt->bindValue(':query_id', $query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':work_id', $work->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $query;
  }

  public function insert(data_company $company, data_query $query,
                          data_query2work $work){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':query_id', $query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':work_id', $work->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time_open', $work->get_time_open(), PDO::PARAM_INT);
    $stmt->bindValue(':time_close', $work->get_time_close(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

  public function get_works(data_company $company, data_query $query){
    $stmt = $this->pdo->prepare(self::$many);
    $stmt->bindValue(':query_id', (int) $query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $works = [];
    while($row = $stmt->fetch())
      $works[] = $this->create_object($row);
    return $works;
  }

  public function init_works(data_company $company, data_query $query){
    $works = $this->get_works($company, $query);
    if(!empty($works))
      foreach($works as $work)
        $query->add_work($work);
  }

  public function update_works(data_company $company, data_query $query){
    $old = [];
    $works = $this->get_works($company, $query);
    if(!empty($works))
      foreach($works as $work)
        $old[$work->get_id()] = $work;
    $new = $query->get_works();
    $deleted = array_diff_key($old, $new);
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
      foreach($inserted as $work)
        $this->insert($company, $query, $work);
    if(!empty($deleted))
      foreach($deleted as $work)
        $this->delete($company, $query, $work);
    return $query;
  }
}