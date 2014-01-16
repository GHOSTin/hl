<?php

class mapper_user2company{

  private static $alert = 'Проблема в мапере соотношения пользователя и компании.';

  private static $find_all = "SELECT DISTINCT `companies`.`id`,
    `companies`.`name` FROM `companies`, `profiles`
    WHERE `profiles`.`user_id` = :user_id
    AND `profiles`.`company_id` = `companies`.`id`";

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':user_id', (int) $this->user->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $companies = [];
    while($row = $stmt->fetch())
      $companies[] = $this->create_object($row);
    return $companies;
  }
}