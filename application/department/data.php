<?php
/*
* Связь с таблицей `departments`.
* В каждой компании свои участки.
*/
final class data_department extends data_object{

  private $company_id;
  private $id;
  private $name;
  private $status;

  private static $statuses = ['active', 'deactive'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods(verify_department), true))
      throw new e_model('Нет доступного метода.');
    return verify_department::$method($args[0]);
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function get_status(){
    return $this->status;
  }

  public function set_id($id){
    $this->id = (int) $id;
    self::verify_id($this->id);
  }

  public function set_name($name){
    $this->name = (string) $name;
    self::verify_name($this->name);
  }

  public function set_status($status){
    $this->status = (string) $status;
    self::verify_status($this->status);
  }
}