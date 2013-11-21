<?php
class mapper_query2number{

  private $company;
  private $query;

  private static $all = "SELECT `query2number`.* , `numbers`.`number`,
    `numbers`.`fio`, `flats`.`flatnumber`
    FROM `query2number`, `numbers`, `flats`
    WHERE `query2number`.`company_id` = :company_id
    AND `numbers`.`company_id` = :company_id
    AND `query2number`.`query_id` = :query_id
    AND `query2number`.`number_id` = `numbers`.`id`
    AND `numbers`.`flat_id` = `flats`.`id`";

  private static $delete = "DELETE FROM `query2number`
    WHERE `company_id` = :company_id
    AND `query_id` = :query_id AND `number_id` = :number_id";

  private static $insert = "INSERT INTO `query2number`
    (`query_id`, `number_id`, `company_id`, `default`) 
    VALUES (:query_id, :number_id, :company_id, :default)";

  public function __construct($company, $query){
    $this->company = $company;
    $this->query = $query;
    data_company::verify_id($this->company->get_id());
    data_query::verify_id($this->query->get_id());
  }

  public function create_object(array $row){
    $number = new data_number();
    $number->set_id($row['number_id']);
    $number->set_number($row['number']);
    $number->set_fio($row['fio']);
    $flat = new data_flat();
    $flat->set_number($row['flatnumber']);
    $number->set_flat($flat);
    return $number;
  }

  private function delete(data_number $number){
    data_number::verify_id($number->get_id());
    $sql = new sql();
    $sql->query(self::$delete);
    $sql->bind(':query_id', (int) $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':number_id', (int) $number->get_id(), PDO::PARAM_INT);
    $sql->execute('Ошибка при исключении лицевого из заявки.');
  }

  private function insert(data_number $number){
    data_number::verify_id($number->get_id());
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':query_id', (int) $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':default', 'false', PDO::PARAM_STR);
    $sql->bind(':number_id', (int) $number->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при добавлении связи заявка-лицевой счет.');
    return $number;
  }

  private function get_numbers(){
    $sql = new sql();
    $sql->query(self::$all);
    $sql->bind(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при запросе связи заявка-лицевой_счет.');
    $stmt = $sql->get_stm();
    $numbers = [];
    while($row = $stmt->fetch())
      $numbers[] = $this->create_object($row);
    $stmt->closeCursor();
    return $numbers;
  }


  private function get_numbers_for_collection_query(array $ids){
    $sql = new sql();
    $sql->query(self::$all);
    $sql->bind(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при запросе связи заявка-лицевой_счет.');
    $stmt = $sql->get_stm();
    $numbers = [];
    while($row = $stmt->fetch())
      $numbers[] = $this->create_object($row);
    $stmt->closeCursor();
    return $numbers;
  }

  public function init_numbers(){
    $numbers = $this->get_numbers();
    if(!empty($numbers))
      foreach($numbers as $number)
        $this->query->add_number($number);
    return $this->query;
  }

  public function update(){
    $new = $this->query->get_numbers();
    $old = [];
    $numbers = $this->get_numbers();
    if(!empty($numbers))
      foreach($numbers as $number)
        $old[$number->get_id()] = $number;
    $deleted = array_diff_key($old, $new);
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
      foreach($inserted as $number)
        $this->insert($number);
    if(!empty($deleted))
      foreach($deleted as $number)
        $this->delete($number);
    return $this->query;
  }
}