<?php

class model_error{

  public function delete_error($time, $user_id){
    $mapper = di::get('mapper_error');
    $error = $mapper->find($time, $user_id);
    if(!($error instanceof data_error))
      throw new e_model('Нет такой ошибки!');
    $mapper->delete($error);
  }

  public function send_error($text){
    $e = ['text' => $text, 'user' => di::get('user'), 'time' => time()];
    return di::get('mapper_error')->insert(di::get('factory_error')->build($e));
  }

  public function get_errors(){
    return di::get('mapper_error')->find_all();
  }
}