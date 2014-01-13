<?php

class mapper_query2comment{

  private $pdo;
  private $company;
  private $query;

  private static $alert = 'Проблема в мапере соотношения заявки и комментариев.';

  private static $find_all = "SELECT `company_id`, `query_id`, `user_id`, `time`,
    `message` FROM `query2comment` WHERE `company_id` = :company_id
    AND `query_id` = :query_id ORDER BY `time`";

  public function __construct(PDO $pdo, data_company $company, data_query $query){
    $this->pdo = $pdo;
    $this->company = $company;
    $this->query = $query;
  }

  private function create_object(array $row){
    $comment = new data_query2comment();
    $comment->set_time($row['time']);
    $comment->set_message($row['message']);
    return $comment;
  }

  private function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':query_id', $this->query->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $comments = [];
    while($row = $stmt->fetch())
      $comments[] = $this->create_object($row);
    return $comments;
  }

  public function init_comments(){
    $comments = $this->find_all();
    if(!empty($comments))
      foreach($comments as $comment)
        $this->query->add_comment($comment);
    return $this->query;
  }
}