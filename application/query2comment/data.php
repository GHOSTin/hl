<?php

class data_query2comment{

  private $time;
  private $message;
  private $user;

  public function __construct(data_user $user){
    $this->user = $user;
  }

  public function get_user(){
    return $this->user;
  }

  public function get_message(){
    return $this->message;
  }

  public function get_time(){
    return $this->time;
  }

  public function set_time($time){
    $this->time = (int) $time;
  }

  public function set_message($message){
    $this->message = (string) $message;
  }
}