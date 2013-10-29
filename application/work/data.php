<?php
/*
* Связь с таблицей `works`.
* В каждой компании свои работы.
*/
final class data_work extends data_object{

	private $company_id;
  private $id;
  private $name;
  private $status;
	private $workgroup_id;

  public function set_id($id){
    $this->id = (int) $id;
  }

  public function set_name($name){
    $this->name = (string) $name;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_work::$value($this);
  }
}