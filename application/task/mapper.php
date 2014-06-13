<?php

class mapper_task extends mapper{

  private static $id = "SELECT MAX(id) as id
    FROM tasks WHERE id > :id";

  private static $insert = "INSERT INTO tasks
    SET id = :id, description = :description, time_open = :time_open, time_close = :time_close,
    time_target = :time_target, rating = :rating, reason = :reason, status = :status";

  private static $find_active_tasks = "SELECT t.*, GROUP_CONCAT(u2t.user_id) as users_id,
    GROUP_CONCAT(u2t.user_type) AS users_type FROM tasks as t, user2task as u2t
    WHERE t.id = u2t.task_id AND t.id IN (SELECT DISTINCT task_id FROM user2task WHERE user_id = :user_id)
    AND (t.status = 'open' OR t.status = 'reopen') GROUP BY t.id ORDER BY t.time_close";

  private static $find = "SELECT t.*, GROUP_CONCAT(u2t.user_id) as users_id,
    GROUP_CONCAT(u2t.user_type) AS users_type FROM tasks as t, user2task as u2t
    WHERE t.id = u2t.task_id AND t.id = :id";


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

  public function find_active_tasks(){
    $stmt = $this->pdo->prepare(self::$find_active_tasks);
    $stmt->bindValue(':user_id', di::get('user')->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $tasks = [];
    $task_factory = di::get('factory_task');
    $user_model = di::get('model_user');
    while($row = $stmt->fetch()){
      $data = ['id'=> $row['id'], 'description'=>$row['description'], 'time_open'=>$row['time_open'],
        'time_close'=>$row['time_close'], 'time_target'=>$row['time_target'], 'rating'=>$row['rating'],
        'reason'=>$row['reason'], 'status'=>$row['status']];
      $users_id = explode(',', $row['users_id']);
      $users_type = explode(',', $row['users_type']);
      foreach($users_type as $key=>$type){
        if($type === 'creator')
          $data['creator'] = $user_model->get_user($users_id[$key]);
        else
          $data['performers'][] = $user_model->get_user($users_id[$key]);
      }
      $tasks[] = $task_factory->build($data);
    }
    return $tasks;
  }

  public function find($id){
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    $row = $stmt->fetch();
    $task_factory = di::get('factory_task');
    $user_model = di::get('model_user');
    $data = ['id'=> $row['id'], 'description'=>$row['description'], 'time_open'=>$row['time_open'],
        'time_close'=>$row['time_close'], 'time_target'=>$row['time_target'], 'rating'=>$row['rating'],
        'reason'=>$row['reason'], 'status'=>$row['status']];
    $users_id = explode(',', $row['users_id']);
    $users_type = explode(',', $row['users_type']);
    foreach($users_type as $key=>$type){
      if($type === 'creator')
        $data['creator'] = $user_model->get_user($users_id[$key]);
      else
        $data['performers'][] = $user_model->get_user($users_id[$key]);
    }
    $task = $task_factory->build($data);
    return $task;
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