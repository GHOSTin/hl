<?php
class mapper_number2processing_center{

  private $company;
  private $number;

  private static $sql_get_centers = "SELECT
    `processing_center2number`.`company_id`,
    `processing_center2number`.`processing_center_id`, 
    `processing_center2number`.`number_id`,
    `processing_center2number`.`identifier`, 
    `processing_centers`.`name` as `processing_center_name`
    FROM `processing_center2number`, `processing_centers`
    WHERE `processing_center2number`.`company_id` = :company_id
    AND `processing_center2number`.`processing_center_id` 
    = `processing_centers`.`id`
    AND `processing_center2number`.`number_id` = :number_id
    ORDER BY `processing_centers`.`name`";
  private static $sql_add_center = "INSERT INTO `processing_center2number`
    (`company_id`, `processing_center_id`, `number_id`, `identifier`)
    VALUES (:company_id, :processing_center_id, :number_id, :identifier)";
  private static $sql_delete = "DELETE FROM `processing_center2number`
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `processing_center_id` = :processing_center_id";

  public function __construct(data_company $company, data_number $number){
    $this->company = $company;
    $this->number = $number;
    data_company::verify_id($this->company->get_id());
    data_number::verify_id($this->number->get_id());
  }

  public function insert(data_number2processing_center $n2c){
    $sql = new sql();
    $sql->query(self::$sql_add_center);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':processing_center_id', (int) $n2c->get_id(), PDO::PARAM_INT);
    $sql->bind(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $sql->bind(':identifier', (string) $n2c->get_identifier(), PDO::PARAM_STR);
    $sql->execute('Ошибка при добавлении центра в лицевой счет.');
  }

  public function delete(data_number2processing_center $n2c){
    $sql = new sql();
    $sql->query(self::$sql_delete);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':processing_center_id', (int) $n2c->get_id(), PDO::PARAM_INT);
    $sql->bind(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    return $sql->execute('Проблема при ислючении идентификатора расчетного центра.');
  }

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['processing_center_id']);
    $center->set_name($row['processing_center_name']);
    $n2c = new data_number2processing_center($center);
    $n2c->set_identifier($row['identifier']);
    return $n2c;
  }

  public function init_processing_centers(){
    $centers = $this->get_processing_centers();
    if(!empty($centers))
      foreach($centers as $center)
        $this->number->add_processing_center($center);
  }

  private function get_processing_centers(){
    $sql = new sql();
    $sql->query(self::$sql_get_centers);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при при выборке расчетных центров.');
    $stmt = $sql->get_stm();
    $centers = [];
    while($row = $stmt->fetch())
      $centers[] = $this->create_object($row);
    return $centers;
  }

  public function update(){
    $new = $this->number->get_processing_centers();
    $old = [];
    $centers = $this->get_processing_centers();
    if(!empty($centers))
      foreach($centers as $center)
        $old[$center->get_id()] = $center;
    $deleted = array_diff_key($old, $new);
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
      foreach($inserted as $center)
        $this->insert($center);
    if(!empty($deleted))
      foreach($deleted as $center)
        $this->delete($center);
    return $this->number;
  }
}