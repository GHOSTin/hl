<?php
class mapper_house{

  private static $one = "SELECT `houses`.`id`, `houses`.`city_id`,
    `houses`.`street_id`, `houses`.`department_id`, `houses`.`status`,
    `houses`.`housenumber`, `streets`.`name`
    FROM `houses`, `streets` WHERE `houses`.`id` = :house_id
    AND `houses`.`street_id` = `streets`.`id`";

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
    $street = new data_street();
    $street->set_id($row['street_id']);
    $street->set_name($row['name']);
    $house->set_street($street);
    return $house;
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$one);
    $sql->bind(':house_id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке домов из базы данных.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество домов');
  }
}

