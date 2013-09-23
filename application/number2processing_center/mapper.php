<?php
class mapper_number2processing_center{

  private $company;
  private $number;
  private static $sql_get_centers = "SELECT `processing_center2number`.`company_id`,
    `processing_center2number`.`processing_center_id`, `processing_center2number`.`number_id`,
    `processing_center2number`.`identifier`, `processing_centers`.`name` as `processing_center_name`
    FROM `processing_center2number`, `processing_centers`
    WHERE `processing_center2number`.`company_id` = :company_id
    AND `processing_center2number`.`processing_center_id` = `processing_centers`.`id`
    AND `processing_center2number`.`number_id` = :number_id
    ORDER BY `processing_centers`.`name`";

  public function __construct(data_company $company, data_number $number){
    $this->company = $company;
    $this->number = $number;
    $this->company->verify('id');
    $this->number->verify('id');
  }

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['center_id']);
    $center->set_name($row['name']);
    $n2c = new data_number2processing_center($this->number, $center);
    $n2c->set_identifier($row['identifier']);
    return $n2c;
  }

  public function init_processing_centers(){
    $centers = $this->get_processing_centers();
    if(!empty($centers))
      foreach($centers as $center)
        $this->number->add_processing_center($center);
  }

  public function get_processing_centers(){
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
}