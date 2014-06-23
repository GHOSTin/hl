<?php

class factory_task2comment {

  public function build(array $row){
    $comment = new data_task2comment();
    $comment->set_user($row['user']);
    $comment->set_time($row['time']);
    $comment->set_message($row['message']);
    return $comment;
  }

} 