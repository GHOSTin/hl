<?php
/*
* Связь с таблицей `flats`.
* Квартиры глобальны, но пока привязаны к компании.
*/
final class data_flat extends data_object{

  private $company_id;
  private $house;
  private $id;
  private $number;
  private $status;

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_house(){
    return $this->house;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_status(){
    return $this->status;
  }

  public function set_company_id($id){
    $this->company_id = (int) $id;
  }

  public function set_house(data_house $house){
    $this->house = $house;
  }

  public function set_id($id){
    $this->id = (int) $id;
  }

  public function set_number($number){
    $this->number = (string) $number;
  }

  public function set_status($status){
    $this->status = (string) $status;
  }

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_flat::$value($this);
  }
}