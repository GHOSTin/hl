<?php
/*
* Связь с таблицей `companies`.
* Компании глобальны для системы.
*/
final class data_company extends data_object{

	private $id;
  private $name;
	private $status;

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
  }

  public function set_name($name){
    $this->name = (string) $name;
  }

  public function set_status($status){
    $this->status = (string) $status;
  }

  public static function verify_id($id){
    if($id > 255 OR $id < 1)
      throw new e_model('Идентификатор компании задан не верно.');
  }
}