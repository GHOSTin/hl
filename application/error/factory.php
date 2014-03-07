<?php

class factory_error{

  public function build(array $data){
    $error = new data_error();
    $error->set_text($data['text']);
    $error->set_user($data['user']);
    $error->set_time($data['time']);
    return $error;
  }
}