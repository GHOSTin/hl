<?php
class mapper_house2processing_center{

  private $company;
  private $house;
  private $centers;

  public function __construct(data_company $company, data_house $house){
      $this->company = $company;
      $this->house = $house;
      $this->company->verify('id');
      $this->house->verify('id');
  }

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['center_id']);
    $center->set_name($row['name']);
    return [$center, $row['identifier']];
  }

  private function delete(data_processing_center $center){
        $center->verify('id');
        $sql = new sql();
        $sql->query("DELETE FROM `house2processing_center` 
                    WHERE `company_id` = :company_id AND `house_id` = :house_id
                    AND `center_id` = :center_id");
        $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
        $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
        $sql->bind(':center_id', (int) $center->get_id(), PDO::PARAM_INT);
        $sql->execute('Проблема при удалении связи.');
    }

  public function init_processing_centers(){
    $centers = $this->get_processing_centers();
    if(!empty($centers))
      foreach($centers as $value)
        $this->house->add_processing_center($value[0], $value[1]);
  }

  private function insert(data_processing_center $center, $identifier){
      $center->verify('id');
      $sql = new sql();
      $sql->query("INSERT INTO `house2processing_center` (`company_id`, 
                  `house_id`, `center_id`, `identifier`) 
                  VALUES (:company_id, :house_id, :center_id, :identifier)");
      $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
      $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
      $sql->bind(':center_id', (int) $center->get_id(), PDO::PARAM_INT);
      $sql->bind(':identifier', (string) $identifier, PDO::PARAM_STR);
      $sql->execute('Проблема при добавлении связи.');
  }

  private function get_processing_centers(){
    $sql = new sql();
    $sql->query("SELECT `house2processing_center`.`center_id`,
                `house2processing_center`.`identifier`,
                `processing_centers`.`name`
                FROM `house2processing_center`, `processing_centers`
                WHERE `house2processing_center`.`company_id` = :company_id
                AND `house2processing_center`.`house_id` = :house_id
                AND `house2processing_center`.`center_id` = `processing_centers`.`id`");
    $sql->bind(':house_id', $this->house->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при запросе связи дом-процессинговый центр.');
    $stmt = $sql->get_stm();
    $centers = [];
    if($stmt->rowCount() > 0)
    while($row = $stmt->fetch())
      $centers[] = $this->create_object($row);
    $stmt->closeCursor();
    return $centers;
  }

  public function update(){
    $new = $this->house->get_processing_centers();
    $old = $this->get_processing_centers();
    $deleted = array_diff_key($old, $new);
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
        foreach($inserted as $center)
            $this->insert($center[0], $center[1]);
    if(!empty($deleted))
        foreach($deleted as $center)
            $this->delete($center[0]);
    return $this->house;
  }
}