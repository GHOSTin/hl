<?php

class data_query2comment{

  private $time;
  private $message;

  public function get_message(){
    return $this->message;
  }

  public function set_time($time){
    $this->time = (int) $time;
  }

  public function set_message($message){
    $this->message = (string) $message;
  }
}