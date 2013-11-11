<?php
class mapper_street{

  private static $many = "SELECT DISTINCT `id`, `company_id`, 
    `city_id`, `status`, `name` FROM `streets` ORDER BY `name`";

  private static $one = "SELECT DISTINCT `id`, `company_id`, 
    `city_id`, `status`, `name` FROM `streets` WHERE `id` = :id";

  public function create_object(array $row){
    $street = new data_street();
    $street->set_id($row['id']);
    $street->set_status($row['status']);
    $street->set_name($row['name']);
    return $street;
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$one);
    $sql->bind(':id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке улиц');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное чисто записей');
  }

  public function get_streets(){
    $sql = new sql();
    $sql->query(self::$many);
    $sql->execute('Проблема при выборке улиц');
    $streets = [];
    $stmt = $sql->get_stm();
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $streets[] = $this->create_object($row);
    return $streets;
  }
}