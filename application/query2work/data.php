<?php
class data_query2work{

  private $work;
  private $time_open;
  private $time_close;
  private $value;

  public function __call($method, $args){
    return $this->work->$method($args);
  }

  public function __construct(data_work $work){
    $this->work = $work;
  }

  public function set_time_open($time){
    $this->time_open = (int) $time;
  }

  public function set_time_close($time){
    $this->time_close = (int) $time;
  }

  public function set_value($value){
    $this->value = (string) $value;
  }

  public function get_time_open(){
    return $this->time_open;
  }

  public function get_time_close(){
    return $this->time_close;
  }

  public function get_value(){
    return $this->value;
  }
}