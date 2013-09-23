<?php
class mapper_house{

  private static $sql_find = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
    `department_id`, `status`, `housenumber` as `number`
    FROM `houses` WHERE `id` = :house_id";

  public function create_object(array $row){
    $house = new data_house();
    $house->set_id($row['id']);
    return $house;
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$sql_find);
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