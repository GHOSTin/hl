<?php

class model_task {

  public function add_task($title, $description, $time_target, array $performers){
    $pdo = di::get('pdo');
    $pdo->beginTransaction();
    $mapper = di::get('mapper_task');
    $model_user = di::get('model_user2task');
    $id = $mapper->get_insert_id();
    $data = ['id'=> $id, 'title'=> $title, 'description'=> $description, 'time_open'=> time(), 'creator'=> di::get('user'),
      'time_target'=> $time_target, 'time_close'=> null, 'rating'=> 0, 'reason'=> null, 'status'=> 'open'];
    $data['performers'] = [];
    foreach($performers as $value){
      $user = di::get('em')->find('data_user', $value);
      array_push($data['performers'], $user);
    }
    $task = di::get('factory_task')->build($data);
    $mapper->insert($task);
    $model_user->add_users($task);
    $pdo->commit();
    return $task;
  }

  public function save_task($id, $title, $description, $time_target, array $performers,array $options) {
    $pdo = di::get('pdo');
    $pdo->beginTransaction();
    $mapper = di::get('mapper_task');
    $model_user = di::get('model_user2task');
    $task = $mapper->find($id);
    $task->set_title($title);
    $task->set_description($description);
    $task->set_time_target($time_target);
    if($options['status'] == 'close'){
      $task->set_reason($options['reason']);
      $task->set_rating((int) substr($options['rating'], -1));
      $task->set_time_close($options['time_close']);
    }
    $task_performers = [];
    foreach($performers as $value){
      $user = di::get('em')->find('data_user', $value);
      array_push($task_performers, $user);
    }
    $task->set_performers($task_performers);
    $mapper->update($task);
    $model_user->update($task);
    $pdo->commit();
    return $task;
  }

  public function close_task($id, $reason, $rating, $time_close){
    $pdo = di::get('pdo');
    $pdo->beginTransaction();
    $mapper = di::get('mapper_task');
    $task = $mapper->find($id);
    $task->set_reason($reason);
    $task->set_rating((int) substr($rating, -1));
    $task->set_time_close($time_close);
    $task->set_status('close');
    $mapper->update($task);
    $pdo->commit();
    return true;
  }

  public function add_comment($task_id, $message){
    $pdo = di::get('pdo');
    $pdo->beginTransaction();
    $mapper = di::get('mapper_task2comment');
    $mapper->insert($task_id, $message);
    $pdo->commit();
    return true;
  }
}