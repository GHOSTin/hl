<?php

class mapper_user2task extends mapper{
  private static $insert = "INSERT INTO user2task
    SET task_id = :task_id, user_id = :user_id, user_type = :user_type";

  public function insert($task_id, $user_id, $user_type){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_type', $user_type, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
} 