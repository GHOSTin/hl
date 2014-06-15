<?php

class model_user2task {

  public function add_users(data_task $task){
    $mapper = di::get('mapper_user2task');
    $mapper->insert($task->get_id(), $task->get_creator()->get_id(), 'creator');
    foreach($task->get_performers() as $performer)
      $mapper->insert($task->get_id(), $performer->get_id(), 'performer');
  }

  public function update(data_task $task){
    $mapper = di::get('mapper_user2task');
    $mapper->delete_performers($task->get_id());
    foreach($task->get_performers() as $performer)
      $mapper->insert($task->get_id(), $performer->get_id(), 'performer');
  }
} 