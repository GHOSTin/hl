<?php
final class data_query_work_type extends data_object{

  private $id;
  private $name;

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods('verify_query_work_type'), true))
      throw new BadMethodCallException();
    return verify_query_work_type::$method($args[0]);
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function set_id($id){
    $this->id = (int) $id;
    self::verify_id($this->id);
  }

  public function set_name($name){
    $this->name = (string) $name;
    self::verify_name($this->name);
  }
}