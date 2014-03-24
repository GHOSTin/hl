<?php

class mapper_query2comment extends mapper{

  private static $find_all = "SELECT `query2comment`.`time`,
    `query2comment`.`message`, `users`.`id`, `users`.`lastname`,
    `users`.`firstname`, `users`.`midlename`
    FROM `query2comment`, `users`
    WHERE `query2comment`.`company_id` = :company_id
    AND `query2comment`.`query_id` = :query_id
    AND `query2comment`.`user_id` = `users`.`id`
    ORDER BY `query2comment`.`time`";

  private static $insert = 'INSERT `query2comment` (`company_id`, `query_id`,
    `user_id`, `time`, `message`) VALUES (:company_id, :query_id, :user_id,
    :time, :message)';

  private function create_object(array $row){
    $user = new data_user();
    $user->set_id($row['id']);
    $user->set_lastname($row['lastname']);
    $user->set_firstname($row['firstname']);
    $user->set_middlename($row['midlename']);
    $c_array = ['user' => $user, 'time' => $row['time'],
      'message' => $row['message']];
    return di::get('factory_query2comment')->build($c_array);
  }

  public function find_all(data_company $company, data_query $query){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':query_id', $query->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $comments = [];
    while($row = $stmt->fetch())
      $comments[] = $this->create_object($row);
    return $comments;
  }

  public function init_comments(data_company $company, data_query $query){
    $comments = $this->find_all($company, $query);
    if(!empty($comments))
      foreach($comments as $comment)
        $query->add_comment($comment);
    return $query;
  }

  public function insert(data_company $company, data_query $query, data_query2comment $comment){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':query_id', $query->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $comment->get_user()->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time', $comment->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':message', $comment->get_message(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

  public function update(data_company $company, data_query $query){
    $old = [];
    $comments = $this->find_all($company, $query);
    if(!empty($comments))
      foreach($comments as $comment){
        $id = $comment->get_user()->get_id().'_'.$comment->get_time();
        $old[$id] = $comment;
      }
    $new = $query->get_comments();
    $inserted = array_diff_key($new, $old);
    if(!empty($inserted))
      foreach($inserted as $comment)
        $this->insert($company, $query, $comment);
    return $query;
  }
}