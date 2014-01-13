<?php

class mapper_query2comment{

  private $pdo;
  private $company;
  private $query;

  private static $alert = 'Проблема в мапере соотношения заявки и комментариев.';

  private static $find_all = "SELECT `company_id`, `query_id`, `user_id`, `time`,
    `message` FROM `query2comment` WHERE `company_id` = :company_id
    AND `query_id` = :query_id";

  public function __construct(PDO $pdo, data_company $company, data_query $query){
    $this->pdo = $pdo;
    $this->company = $company;
    $this->query = $query;
  }

  private function create_object(array $row){
  }

  private function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $comments = [];
    while($row = $stmt->fetch())
      $comments[] = $this->create_object();
    return $comments;
  }

  public function init_comments(){
    $comments = $this->find_all();
    var_dump($comments);
    exit();
    return $this->query;
  }
}