<?php
class data_query2user{

  private $user;
  private $class;

  public function __call($method, $args){
    return $this->user->$method($args);
  }

  public function __construct(data_user $user){
    $this->user = $user;
  }

  public function set_class($class){
    $this->class = (string) $class;
  }

  public function get_class(){
    return $this->class;
  }
}