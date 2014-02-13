<?php
class mapper_house2flat{

  private $company;
  private $house;
  private $pdo;

  private static $get_flats = "SELECT `id`, `house_id`, `status`,
    `flatnumber` as `number` FROM `flats` WHERE `house_id` = :house_id
    ORDER BY (`flatnumber` + 0)";

  private static $insert = "INSERT `flats` (`id`, `company_id`, `house_id`,
    `status`, `flatnumber`) VALUES (:id, :company_id,
    :house_id, :status, :number)";

  private static $id = "SELECT MAX(`id`) as `max_flat_id` FROM `flats`";

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    data_company::verify_id($this->company->get_id());
    data_house::verify_id($this->house->get_id());
    $this->pdo = di::get('pdo');
  }

  public function get_flats(){
    $stmt = $this->pdo->prepare(self::$get_flats);
    $stmt->bindValue(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $flats = [];
    $factory = new factory_flat();
    while($row = $stmt->fetch())
      $flats[] = $factory->create($row);
    return $flats;
  }

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    $flat_id = (int) $stmt->fetch()['max_flat_id'] + 1;
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
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':id', (int) $flat->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':house_id', (int) $this->house->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':status', (string) $flat->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':number', (string) $flat->get_number(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $flat;
  }

  private function verify(data_flat $flat){
    data_flat::verify_id($flat->get_id());
    data_flat::verify_number($flat->get_number());
    data_flat::verify_status($flat->get_status());
  }
}