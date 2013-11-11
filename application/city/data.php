<?php
/*
* Связь с таблицей `cities`.
* Города глобальны, но пока привязаны к компании.
*/
final class data_city extends data_object{
	
	private $id;
  private $name;
	private $status;

  private static $statuses = ['false', 'true'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods(verify_user), true))
      throw new e_model('Нет доступного метода.');
    return verify_user::$method($args[0]);
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
    $id = (int) $id;
    self::verify_id($id);
    $this->id = $id;
  }

  public function set_name($name){
    self::verify_name($name);
    $this->name = $name;
  }

  public function set_status($status){
    self::verify_status($status);
    $this->status = $status;
  }
}