<?php

class mapper_user2company{

  private $pdo;
  private $user;

  private static $find_all = "SELECT DISTINCT `companies`.`id`,
    `companies`.`name` FROM `companies`, `profiles`
    WHERE `profiles`.`user_id` = :user_id
    AND `profiles`.`company_id` = `companies`.`id`";

  public function __construct(PDO $pdo, data_user $user){
    $this->pdo = $pdo;
    $this->user = $user;
  }

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $companies = [];
    while($row = $stmt->fetch())
      $companies[] = di::get('factory_company')->build($row);
    return $companies;
  }
}