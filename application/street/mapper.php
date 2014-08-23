<?php

class mapper_street extends mapper{

  private static $many = "SELECT DISTINCT `id`, `company_id`,
    `city_id`, `status`, `name` FROM `streets` ORDER BY `name`";

  private static $one = "SELECT DISTINCT `id`, `company_id`,
    `city_id`, `status`, `name` FROM `streets` WHERE `id` = :id";

  public function find($id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    $factory = di::get('factory_street');
    if($count === 0)
      return null;
    elseif($count === 1)
      return $factory->build($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function get_streets(){
    $stmt = $this->pdo->prepare(self::$many);
    if(!$stmt->execute())
      throw new RuntimeException();
    $streets = [];
    $factory = di::get('factory_street');
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $streets[] = $factory->build($row);
    return $streets;
  }
}