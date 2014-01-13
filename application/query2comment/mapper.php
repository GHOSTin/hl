<?php

class mapper_query2comment{

  private $pdo;
  private $company;
  private $query;

  private static $alert = 'Проблема в мапере соотношения заявки и комментариев.';

  private static $find_all = "SELECT `query2comment`.`time`,
    `query2comment`.`message`, `users`.`id`, `users`.`lastname`,
    `users`.`firstname`, `users`.`midlename`
    FROM `query2comment`, `users`
    WHERE `query2comment`.`company_id` = :company_id
    AND `query2comment`.`query_id` = :query_id
    AND `query2comment`.`user_id` = `users`.`id`
    ORDER BY `query2comment`.`time`";

  public function __construct(PDO $pdo, data_company $company, data_query $query){
    $this->pdo = $pdo;
    $this->company = $company;
    $this->query = $query;
  }

  private function create_object(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_lastname($row['lastname']);
    $user->set_firstname($row['firstname']);
    $user->set_middlename($row['midlename']);
    $comment = new data_query2comment($user);
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

  public function update(){
    $old = [];
    $comments = $this->find_all();
    if(!empty($comments))
      foreach($comments as $comment){
        $id = $comment->get_user()->get_id().'_'.$comment->get_time();
        $old[$id] = $comment;
      }
    $new = $this->query->get_comments();
    $deleted = array_diff_key($old, $new);
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
        foreach($inserted as $work)
            $this->insert($work);
    if(!empty($deleted))
        foreach($deleted as $work)
            $this->delete($work);
    return $this->query;
  }
}