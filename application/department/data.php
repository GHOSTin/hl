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

  public function get_company_id(){
    return $this->company_id;
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

  public function set_company_id($id){
    $this->company_id = (int) $id;
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

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_department::$value($this);
  }
}