<?php

class model_error{

  public function delete_error($time, $user_id){
    $mapper = new mapper_error(di::get('pdo'));
    $error = $mapper->find($time, $user_id);
    if(!($error instanceof data_error))
      throw new e_model('Нет такой ошибки!');
    $mapper->delete($error);
  }

  public function send_error($text){
    $error = new data_error();
    $error->set_text($text);
    $error->set_user(di::get('user'));
    $error->set_time(time());
    return (new mapper_error(di::get('pdo')))->insert($error);
  }

  public function get_errors(){
    return (new mapper_error(di::get('pdo')))->find_all();
  }
}