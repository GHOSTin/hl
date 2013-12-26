<?php
class mapper_house2flat{
  
  private $company;
  private $house;

  private static $sql_get_flats = "SELECT `id`, `house_id`, `status`,
    `flatnumber` as `number` FROM `flats` WHERE `house_id` = :house_id
    ORDER BY (`flatnumber` + 0)";

  private static $sql_insert = "INSERT `flats` (`id`, `company_id`, `house_id`,
    `status`, `flatnumber`) VALUES (:id, :company_id,
    :house_id, :status, :number)";

  private static $id = "SELECT MAX(`id`) as `max_flat_id` FROM `flats`";

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    data_company::verify_id($this->company->get_id());
    data_house::verify_id($this->house->get_id());
  }

  public function get_flats(){
    $sql = new sql();
    $sql->query(self::$sql_get_flats);
    $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблемы при выборке квартир.');
    $stmt = $sql->get_stm();
    $flats = [];
    $factory = new factory_flat();
    while($row = $stmt->fetch())
      $flats[] = $factory->create($row);
    return $flats;
  } 

  public function get_insert_id(){
    $sql = new sql();
    $sql->query(self::$id);
    $sql->execute('Проблема при опредении следующего flat_id.');
    if($sql->count() !== 1)
      throw new e_model('Проблема при опредении следующего flat_id.');
    $flat_id = (int) $sql->row()['max_flat_id'] + 1;
    $sql->close();
    return $flat_id;
  }

  public function init_flats(){
    $flats = $this->get_flats();
    if(!empty($flats))
      foreach($flats as $flat)
        $this->house->add_flat($flat);
  }

  public function insert(data_flat $flat){
    $this->verify($flat);
    $sql = new sql();
    $sql->query(self::$sql_insert);
    $sql->bind(':id', (int) $flat->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $sql->bind(':status', (string) $flat->get_status(), PDO::PARAM_STR);
    $sql->bind(':number', (string) $flat->get_number(), PDO::PARAM_STR);
    $sql->execute('Проблема при добавлении квартиры.');
    return $flat;
  }

  private function verify(data_flat $flat){
    data_flat::verify_id($flat->get_id());
    data_flat::verify_number($flat->get_number());
    data_flat::verify_status($flat->get_status());
  }
}