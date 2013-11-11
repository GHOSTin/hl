<?php
/*
* Связь с таблицей `companies`.
* Компании глобальны для системы.
*/
class data_company{

	private $id;
  private $name;
	private $status;

  public static $statuses = ['true', 'false'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods(verify_company), true))
      throw new e_model('Нет доступного метода.');
    return verify_company::$method($args[0]);
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
    self::verify_id($id);
  }

  public function set_name($name){
    $this->name = (string) $name;
    self::verify_name($name);
  }

  public function set_status($status){
    $this->status = (string) $status;
    self::verify_status($status);
  }
}