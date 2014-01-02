<?php
class data_flat extends data_object{

  private $company_id;
  private $house;
  private $id;
  private $number;
  private $status;

  public static $statuses = ['true', 'false'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods('verify_flat'), true))
      throw new e_model('Нет доступного метода.');
    return verify_flat::$method($args[0]);
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

  public function set_house(data_house $house){
    $this->house = $house;
  }

  public function set_id($id){
    $this->id = (int) $id;
    self::verify_id($this->id);
  }

  public function set_number($number){
    $this->number = (string) $number;
    self::verify_number($this->number);
  }

  public function set_status($status){
    $this->status = (string) $status;
    self::verify_status($this->status);
  }
}