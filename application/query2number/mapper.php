<?php
class mapper_query2number{

  private $company;
  private $query;
  private $pdo;

  private static $all = "SELECT `query2number`.* , `numbers`.`number`,
    `numbers`.`fio`, `numbers`.`email`, `numbers`.`status`, 
    `numbers`.`cellphone`, `numbers`.`telephone`,
    `flats`.`flatnumber`, `flats`.`id` as `f_id`
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
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $n_array = ['id' => $row['number_id'], 'fio' => $row['fio'],
      'number' => $row['number'], 'email' => $row['email'],
      'cellphone' => $row['cellphone'], 'telephone' => $row['telephone'],
      'status' => $row['status']];
    $number = di::get('factory_number')->build($n_array);
    $f_array = ['id' => $row['f_id'], 'number' => $row['flatnumber']];
    $number->set_flat(di::get('factory_flat')->build($f_array));
    return $number;
  }

  private function delete(data_number $number){
    data_number::verify_id($number->get_id());
    $stmt = $this->pdo->prepare(self::$delete);
    $stmt->bindValue(':query_id', (int) $this->query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number_id', (int) $number->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

  private function insert(data_number $number){
    data_number::verify_id($number->get_id());
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':query_id', (int) $this->query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':default', 'false', PDO::PARAM_STR);
    $stmt->bindValue(':number_id', (int) $number->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $number;
  }

  private function get_numbers(){
    $stmt = $this->pdo->prepare(self::$all);
    $stmt->bindValue(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $numbers = [];
    while($row = $stmt->fetch())
      $numbers[] = $this->create_object($row);
    $stmt->closeCursor();
    return $numbers;
  }


  private function get_numbers_for_collection_query(array $ids){
    $stmt = $this->pdo->prepare(self::$all);
    $stmt->bindValue(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
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