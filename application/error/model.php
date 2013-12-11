<?php

class model_error{

  public function send_error($text){
    $error = new data_error();
    $error->set_text($text);
    $error->set_user(model_session::get_user());
    $error->set_time(time());
    return (new mapper_error(db::get_handler()))->insert($error);
  }

  public function get_errors(){
     return (new mapper_error(db::get_handler()))->find_all();
  }
}