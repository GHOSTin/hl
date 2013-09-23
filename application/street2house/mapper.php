<?php
class mapper_street2house{

  private $street;

  private static $sql_init_houses = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
    `department_id`, `status`, `housenumber` as `number`
    FROM `houses` WHERE `street_id` = :street_id  ORDER BY (`houses`.`housenumber` + 0)";

  public function __construct(data_street $street){
    $this->street = $street;
    $this->street->verify('id');
  }

  public function create_object(array $row){
    $house = new data_house();
    $house->set_id($row['id']);
    $house->set_company_id($row['company_id']);
    $house->set_city_id($row['city_id']);
    $house->set_street_id($row['street_id']);
    $house->set_department_id($row['department_id']);
    $house->set_status($row['status']);
    $house->set_number($row['number']);
    return $house;
  }

  private function get_houses(){
    $sql = new sql();
    $sql->query(self::$sql_init_houses);
    $sql->bind(':street_id', $this->street->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке домов.');
    $stmt = $sql->get_stm();
    $houses = [];
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch()){
        $houses[] = $this->create_object($row);
      }
    return $houses;
  }

  public function init_houses(){
    $houses = $this->get_houses();
    if(!empty($houses))
      foreach($houses as $house)
        $this->street->add_house($house);
  }
}