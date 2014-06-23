<?php

class mapper_task2comment extends mapper{

  private static $find_all = "SELECT * FROM task2comment
    WHERE task_id = :task_id";

  private static $insert = "INSERT INTO task2comment
    SET task_id = :task_id, user_id = :user_id, message = :message, time = :time";

  public function find_all($task_id){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $comments = [];
    $user_model = di::get('model_user');
    while($row = $stmt->fetch()){
      $data = ['message'=> $row['message'], 'time'=> $row['time'],
        'user'=> $user_model->get_user($row['user_id'])];
      $comments[] = di::get('factory_task2comment')->build($data);
    }
    return $comments;
  }

  public function insert($task_id, $message){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', di::get('user')->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    $stmt->bindValue(':time', time(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

} 