<?php
/**
* Связь с таблицей `processing_centers`
*/
class data_processing_center extends data_object{

  private $id;
  private $name;

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function set_id($id){
    $id = (int) $id;
    self::verify_id($id);
    $this->id = $id;
  }

  public function set_name($name){
    self::verify_name($name);
    $this->name = (string) $name;
  }

  public static function verify_id($id){
    if($id < 1 OR $id > 255)
      throw new e_model('Идентификатор процессингового центра задан не верно.');
  }

  public static function verify_name($name){
    if(!preg_match('/^[А-Яа-я0-9 -]{2,20}$/u', $name))
        throw new e_model('Название процессингового центра задано не верно.');
  }
}