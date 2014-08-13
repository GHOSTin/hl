<?php

class mapper_company extends mapper{

  private static $find = "SELECT id, name FROM companies WHERE id = :id";
  private static $find_all = "SELECT id, name FROM companies ORDER BY name";

  public function find($id){
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return di::get('factory_company')->build($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    if(!$stmt->execute())
      throw new RuntimeException();
    $companies = [];
    $factory = di::get('factory_company');
    while($row = $stmt->fetch())
      $companies[] = $factory->build($row);
    return $companies;
  }
}