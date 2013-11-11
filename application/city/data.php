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

  /**
  * Верификация идентификатора.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор города задан не верно.');
  }

  /**
  * Верификация названия.
  */
  public static function verify_name($name){
    if(!preg_match('/^[А-Я][а-я]{0,19}$/', $name))
      throw new e_model('Название города задано не верно.');
  }

  /**
  * Верификация статуса.
  */
  public static function verify_status($status){
    if(!in_array($status, self::$statuses, true))
      throw new e_model('Статус города задан не верно.');
  }
}