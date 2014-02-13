<?php

class mapper_company{

  private $pdo;

  private static $find_all = "SELECT `id`, `name` FROM `companies`
    ORDER BY `name`";

  public function __construct(PDO $pdo){
    $this->pdo = $pdo;
  }

  public function create_object(array $row){
    $company = new data_company();
    $company->set_id($row['id']);
    $company->set_name($row['name']);
    return $company;
  }

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    if(!$stmt->execute())
      throw new RuntimeException();
    $companies = [];
    while($row = $stmt->fetch())
      $companies[] = $this->create_object($row);
    return $companies;
  }
}