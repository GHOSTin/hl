<?php

class factory_query2comment{

  public function build(array $data){
    $comment = new data_query2comment();
    $comment->set_user($data['user']);
    $comment->set_time($data['time']);
    $comment->set_message($data['message']);
    return $comment;
  }
}