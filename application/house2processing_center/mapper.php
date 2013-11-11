<?php
class mapper_house2processing_center{

  private $company;
  private $house;
  private $centers;

  private static $delete = "DELETE FROM `house2processing_center` 
    WHERE `company_id` = :company_id AND `house_id` = :house_id
    AND `center_id` = :center_id";

  private static $insert = "INSERT INTO `house2processing_center` 
    (`company_id`, `house_id`, `center_id`, `identifier`) 
    VALUES (:company_id, :house_id, :center_id, :identifier)";

  private static $all = "SELECT `house2processing_center`.`center_id`,
    `house2processing_center`.`identifier`, `processing_centers`.`name`
    FROM `house2processing_center`, `processing_centers`
    WHERE `house2processing_center`.`company_id` = :company_id
    AND `house2processing_center`.`house_id` = :house_id
    AND `house2processing_center`.`center_id` = `processing_centers`.`id`";

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    data_company::verify_id($this->company->get_id());
    data_house::verify_id($this->house->get_id());
  }

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['center_id']);
    $center->set_name($row['name']);
    $h2pc = new data_house2processing_center($center);
    $h2pc->set_identifier($row['identifier']);
    return $h2pc;
  }

  private function delete(data_house2processing_center $center){
    $sql = new sql();
    $sql->query(self::$delete);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $sql->bind(':center_id', (int) $center->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при удалении связи.');
  }

  public function init_processing_centers(){
    $centers = $this->get_processing_centers();
    if(!empty($centers))
      foreach($centers as $center)
        $this->house->add_processing_center($center);
  }

  private function insert(data_house2processing_center $center){
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $sql->bind(':center_id', (int) $center->get_id(), PDO::PARAM_INT);
    $sql->bind(':identifier', (string) $center->get_identifier(), PDO::PARAM_STR);
    $sql->execute('Проблема при добавлении связи.');
  }

  private function get_processing_centers(){
    $sql = new sql();
    $sql->query(self::$all);
    $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при запросе связи дом-процессинговый центр.');
    $stmt = $sql->get_stm();
    $centers = [];
    if($stmt->rowCount() > 0)
    while($row = $stmt->fetch())
      $centers[] = $this->create_object($row);
    $stmt->closeCursor();
    return $centers;
  }

  public function update_processing_centers(){
    try{
      sql::begin();
      $new = $this->house->get_processing_centers();
      $old = $this->get_processing_centers();
      $deleted = array_diff_key($old, $new);
      $inserted = array_diff_key($new, $old);
      if(!empty($inserted))
        foreach($inserted as $center)
          $this->insert($center);
      if(!empty($deleted))
        foreach($deleted as $center)
          $this->delete($center);
      sql::commit();
      return $this->house;
    }catch(exception $e){
      sql::rollback();
      throw new e_model('Проблема при обновлении списка процессинговых центров.');
    }
  }
}