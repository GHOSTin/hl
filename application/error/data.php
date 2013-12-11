<?php
class data_error{

  private $text;
  private $user;
  private $time;

  public function get_user(){
    return $this->user;
  }

  public function get_text(){
    return $this->text;
  }

  public function set_user(data_user $user){
    $this->user = $user;
  }

  public function set_text($text){
    $this->text = (string) $text;
  }

  public function set_time(){
    $this->time = (int) $time;
  }
}