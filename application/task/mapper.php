<?php

class mapper_task extends mapper{

  private static $id = "SELECT MAX(id) as id
    FROM tasks WHERE id > :id";

  private static $insert = "INSERT INTO tasks
    SET id = :id, description = :description, time_open = :time_open, time_close = :time_close,
    time_target = :time_target, rating = :rating, reason = :reason, status = :status";

  public function get_insert_id(){
    $prefix = date('Ymd');
    $stmt = $this->pdo->prepare(self::$id);
    $stmt->bindValue(':id', $prefix.'000000', PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    $id = $stmt->fetch()['id'];
    if(is_null($id))
      return $prefix.'000001';
    else
      return $id + 1;
  }

  public function insert(data_task $task){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':id', $task->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':description', $task->get_description(), PDO::PARAM_STR);
    $stmt->bindValue(':time_open', $task->get_time_open(), PDO::PARAM_INT);
    $stmt->bindValue(':time_close', $task->get_time_close(), PDO::PARAM_INT);
    $stmt->bindValue(':time_target', $task->get_time_target(), PDO::PARAM_INT);
    $stmt->bindValue(':rating', $task->get_rating(), PDO::PARAM_INT);
    $stmt->bindValue(':reason', $task->get_reason(), PDO::PARAM_STR);
    $stmt->bindValue(':status', $task->get_status(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }

} 