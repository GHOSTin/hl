<?php
class mapper_house{

  private static $alert = 'Проблема в мапере дома';

  private static $one = "SELECT `houses`.`id`, `houses`.`city_id`,
    `houses`.`street_id`, `houses`.`department_id`, `houses`.`status`,
    `houses`.`housenumber`, `streets`.`name`
    FROM `houses`, `streets` WHERE `houses`.`id` = :house_id
    AND `houses`.`street_id` = `streets`.`id`";

  public function __construct(){
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $house = new data_house();
    $house->set_id($row['id']);
    $house->set_number($row['housenumber']);
    $house->set_status($row['status']);
    // city
    $city = new data_city();
    $city->set_id($row['city_id']);
    $house->set_city($city);
    // department
    $department = new data_department();
    $department->set_id($row['department_id']);
    $house->set_department($department);
    // street
    $street = ['id' => $row['street_id'], 'name' => $row['name']];
    $house->set_street((new factory_street)->create($street));
    return $house;
  }

  public function find($id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':house_id', (int) $id, PDO::PARAM_INT);
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
}

