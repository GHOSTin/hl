<?php
class mapper_house2flat{
  
  private $company;
  private $house;

  private static $sql_get_flats = "SELECT `id`, `house_id`, `status`, `flatnumber`
          FROM `flats` WHERE `house_id` = :house_id ORDER BY (`flatnumber` + 0)";

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    $this->company->verify('id');
    $this->house->verify('id');
  }

  public function create_object(array $row){
    $flat = new data_flat();
    $flat->set_id($row['id']);
    $flat->set_number($row['flatnumber']);
    return $flat;
  }

  public function get_flats(){
    $sql = new sql();
    $sql->query(self::$sql_get_flats);
    $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблемы при выборке квартир.');
    $stmt = $sql->get_stm();
    $flats = [];
    while($row = $stmt->fetch())
      $flats[] = $this->create_object($stmt->fetch());
    return $flats;
  } 

  public function init_flats(){
    $flats = $this->get_flats();
    if(!empty($flats))
      foreach($flats as $flat)
        $this->house->add_flat($flat);
  }
}