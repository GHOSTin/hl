<?php

class model_task {

  public function add_task($description, $time_close, array $performers){
    $pdo = di::get('pdo');
    $pdo->beginTransaction();
    $mapper = di::get('mapper_task');
    $model_user = di::get('model_user2task');
    $id = $mapper->get_insert_id();
    $data = ['id'=> $id, 'description'=> $description, 'time_open'=> time(), 'creator'=> di::get('user'),
      'time_close'=> $time_close, 'time_target'=> null, 'rating'=> 0, 'reason'=> null, 'status'=> 'open'];
    $data['performers'] = [];
    foreach($performers as $value){
      $user = di::get('model_user')->get_user($value);
      array_push($data['performers'], $user);
    }
    $task = di::get('factory_task')->build($data);
    $mapper->insert($task);
    $model_user->add_users($task);
    $pdo->commit();
    return $task;
  }
} 