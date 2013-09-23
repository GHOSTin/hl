<?php
class mapper_street{

  private static $sql_get_streets = "SELECT DISTINCT `id`, `company_id`, 
    `city_id`, `status`, `name` FROM `streets` ORDER BY `name`";

  public function create_object(array $row){
    $street = new data_street();
    $street->set_id($row['id']);
    $street->set_company_id($row['company_id']);
    $street->set_city_id($row['city_id']);
    $street->set_status($row['status']);
    $street->set_name($row['name']);
    return $street;
  }

  public function get_streets(){
    $sql = new sql();
    $sql->query(self::$sql_get_streets);
    $sql->execute('Проблема при выборке улиц');
    $streets = [];
    $stmt = $sql->get_stm();
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $streets[] = $this->create_object($row);
    return $streets;
  }
}