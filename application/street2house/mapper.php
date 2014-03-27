<?php

class mapper_street2house extends mapper{

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

  public function create_object(data_street $street, array $row){
    $h_array = ['id' => $row['id'], 'number' => $row['number'],
      'street' => $street, 'status' => $row['status']];
    $house = di::get('factory_house')->build($h_array);
    $city = new data_city();
    $city->set_id($row['city_id']);
    $house->set_city($city);
    return $house;
  }

  public function get_houses(data_street $street){
    $stmt = $this->pdo->prepare(self::$houses);
    $stmt->bindValue(':street_id', $street->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $houses = [];
    while($row = $stmt->fetch())
      $houses[] = $this->create_object($street, $row);
    return $houses;
  }

  public function init_houses(data_street $street){
    $houses = $this->get_houses($street);
    if(!empty($houses))
      foreach($houses as $house)
        $street->add_house($house);
  }

  public function insert(data_street $street, data_house $house){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':house_id', (int) $house->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':street_id', (int) $street->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':status', (string) 'true', PDO::PARAM_STR);
    $stmt->bindValue(':number', (string) $house->get_number(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $house;
  }

  public function find(data_street $street, $id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':house_id', (int) $id, PDO::PARAM_INT);
    $stmt->bindValue(':street_id', (int) $street->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($street, $stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function find_by_number(data_street $street, $number){
    $stmt = $this->pdo->prepare(self::$one_by_number);
    $stmt->bindValue(':number', (string) $number, PDO::PARAM_STR);
    $stmt->bindValue(':street_id', (int) $street->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($street, $stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    return (int) $stmt->fetch()['max_house_id'] + 1;
  }
}