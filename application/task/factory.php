<?php

class factory_task {

  public function build(array $row){
    $task = new data_task();
    $task->set_id($row['id']);
    $task->set_title($row['title']);
    $task->set_description($row['description']);
    $task->set_time_open($row['time_open']);
    $task->set_time_close($row['time_close']);
    $task->set_time_target($row['time_target']);
    $task->set_rating($row['rating']);
    $task->set_creator($row['creator']);
    $task->set_performers($row['performers']);
    $task->set_reason($row['reason']);
    $task->set_status($row['status']);
    return $task;
  }
} 