<?php

class model_error{

  public function delete_error($time, $user_id){
    $mapper = di::get('mapper_error');
    $error = $mapper->find($time, $user_id);
    if(is_null($error))
      throw new RuntimeException();
    $mapper->delete($error);
  }

  public function send_error($text){
    $time = time();
    $user = di::get('user');
    $mapper = di::get('mapper_error');
    $error_array = ['text' => $text, 'user' => $user, 'time' => $time];
    if(!is_null($mapper->find($time, $user->get_id())))
      throw new RuntimeException();
    $error = di::get('factory_error')->build($error_array);
    $mapper->insert($error);
  }
}