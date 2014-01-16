<?php

class mapper_street2house{

  private $street;
  private $pdo;

  private static $alert = 'Проблема в мапере сотношения цлиу и домов.';

  private static $houses = "SELECT `id`, `company_id`, `city_id`, `street_id`,
    `department_id`, `status`, `housenumber` as `number` FROM `houses`
    WHERE `street_id` = :street_id  ORDER BY (`houses`.`housenumber` + 0)";

  private static $one = "SELECT `id`, `company_id`, `city_id`, `street_id`,
    `department_id`, `status`, `housenumber` as `number`
    FROM `houses` WHERE `id` = :house_id AND `street_id` = :street_id";

  private static $one_by_number = "SELECT `id`, `company_id`, `city_id`,
    `street_id`, `department_id`, `status`, `housenumber` as `number`
    FROM `houses` WHERE `housenumber` = :number AND `street_id` = :street_id";

  private static $insert = "INSERT INTO `houses` (`id`, `company_id`, `city_id`,
    `street_id`, `department_id`, `status`, `housenumber`)
    VALUES (:house_id, 1, 1, :street_id, 1, :status, :number)";

  private static $id = "SELECT MAX(`id`) as `max_house_id` FROM `houses`";

  public function __construct(data_street $street){
    $this->street = $street;
    data_street::verify_id($this->street->get_id());
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $house = new data_house();
    $house->set_id($row['id']);
    $house->set_status($row['status']);
    $house->set_number($row['number']);
    $city = new data_city();
    $city->set_id($row['city_id']);
    $house->set_city($city);
    return $house;
  }

  private function get_houses(){
    $stmt = $this->pdo->prepare(self::$houses);
    $stmt->bindValue(':street_id', $this->street->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
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

  public function insert(data_house $house){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':house_id', (int) $house->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':street_id', (int) $this->street->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':status', (string) $house->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':number', (string) $house->get_number(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    return $house;
  }

  public function find($id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':house_id', (int) $id, PDO::PARAM_INT);
    $stmt->bindValue(':street_id', (int) $this->street->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество домов');
  }

  public function find_by_number($number){
    $stmt = $this->pdo->prepare(self::$one_by_number);
    $stmt->bindValue(':number', (string) $number, PDO::PARAM_STR);
    $stmt->bindValue(':street_id', (int) $this->street->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество домов');
  }

  public static function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    if($stmt->rowCount() !== 1)
      throw new e_model('Проблема при опредении следующего house_id.');
    $house_id = (int) $stmt->fetch()['max_house_id'] + 1;
    return $house_id;
  }
}