<?php

class mapper_user2task extends mapper{
  private static $insert = "INSERT INTO user2task
    SET task_id = :task_id, user_id = :user_id, user_type = :user_type";

  private static $delete_performers = "DELETE FROM user2task
    WHERE task_id = :task_id AND user_type = 'performer'";

  public function insert($task_id, $user_id, $user_type){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_type', $user_type, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

  public function delete_performers($task_id){
    $stmt = $this->pdo->prepare(self::$delete_performers);
    $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
} 