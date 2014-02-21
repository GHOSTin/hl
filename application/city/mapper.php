<?php
class mapper_city extends mapper{

  private static $one_by_name = "SELECT `id`, `status`, `name`
    FROM `cities` WHERE `name` = :name";

  private static $one = "SELECT `id`, `status`, `name`
    FROM `cities` WHERE `id` = :id";

  public function create_object(array $row){
    $city = new data_city();
    $city->set_id($row['id']);
    $city->set_status($row['status']);
    $city->set_name($row['name']);
    return $city;
  }

  public function get_city_by_name($name){
    $stmt = $this->pdo->prepare(self::$one_by_name);
    $stmt->bindValue(':name', (string) $name, PDO::PARAM_STR);
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

  public function find($id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
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
}