<?php
class mapper_house{

  private static $one = "SELECT `houses`.`id`, `houses`.`city_id`,
    `houses`.`street_id`, `houses`.`department_id`, `houses`.`status`,
    `houses`.`housenumber`, `streets`.`name`
    FROM `houses`, `streets` WHERE `houses`.`id` = :house_id
    AND `houses`.`street_id` = `streets`.`id`";

  private static $update = 'UPDATE `houses` SET `id` = :id,
    `company_id` = :company_id, `city_id` = :city_id, `street_id` = :street_id,
    `department_id` = :department_id, `status` = :status,
    `housenumber` = :number WHERE `id` = :id';

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
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function update(data_house $house){
    $company = model_session::get_company();
    data_company::verify_id($company->get_id());
    data_house::verify_id($house->get_id());
    data_house::verify_status($house->get_status());
    data_house::verify_number($house->get_number());
    data_city::verify_id($house->get_city()->get_id());
    data_street::verify_id($house->get_street()->get_id());
    data_department::verify_id($house->get_department()->get_id());
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':id', $house->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':city_id', $house->get_city()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':street_id', $house->get_street()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':department_id', $house->get_department()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':status', $house->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':number', $house->get_number(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}

