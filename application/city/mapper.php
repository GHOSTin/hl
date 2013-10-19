<?php
class mapper_city{

  private static $sql_get_city_by_name = "SELECT `id`, `status`, `name`
    FROM `cities` WHERE `name` = :name";

  private static $sql_get_city = "SELECT `id`, `status`, `name`
    FROM `cities` WHERE `id` = :id";

  public function create_object(array $row){
    $city = new data_city();
    $city->set_id($row['id']);
    $city->set_name($row['name']);
    return $city;
  }

  public function get_city_by_name($name){
    $sql = new sql();
    $sql->query(self::$sql_get_city_by_name);
    $sql->bind(':name', (string) $name, PDO::PARAM_STR);
    $sql->execute('Проблема при выборке городов.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество записей.');
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$sql_get_city);
    $sql->bind(':id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке городов.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество записей.');
  }
}