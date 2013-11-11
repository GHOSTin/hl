<?php
/*
* Связь с таблицей `houses`.
* Дома глобальны, но пока привязаны к компании.
*/
final class data_house{
	
	private $city;
	private $company_id;
	private $department_id;
	private $id;
	private $number;
	private $status;
	private $street_id;
	private $street_name;
  private $centers = [];
  private $flats = [];
  private $numbers = [];

  public static $statuses = ['true', 'false'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods(verify_house), true))
      throw new e_model('Нет доступного метода.');
    return verify_house::$method($args[0]);
  }

  public function __construct($id = null){
    $this->id = (int) $id;
  }

  public function add_number(data_number $number){
    if(array_key_exists($number->get_id(), $this->numbers))
      throw new e_model('Дом уже добавлен в улицу.');
    $this->numbers[$number->get_id()] = $number;
  }

  public function add_flat(data_flat $flat){
    if(array_key_exists($flat->get_id(), $this->flats))
      throw new e_model('В доме уже существует такая квартира.');
    $this->flats[$flat->get_id()] = $flat;
  }

  public function get_city(){
    return $this->city;
  }

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_department_id(){
    return $this->department_id;
  }

  public function get_flats(){
    return $this->flats;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_numbers(){
    return $this->numbers;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_street_id(){
    return $this->street_id;
  }

  public function get_street_name(){
    return $this->street_name;
  }

  public function set_city(data_city $city){
    $this->city = $city;
  }

  public function set_company_id($id){
    $this->company_id = (int) $id;
  }

  public function set_department_id($id){
    $this->department_id = (int) $id;
  }

  public function set_id($id){
    $this->id = $id;
  }

  public function set_number($number){
    $this->number = (string) $number;
  }

  public function set_status($status){
    self::verify_status($status);
    $this->status = $status;
  }

  public function set_street_id($id){
    $this->street_id = (int) $id;
  }

  public function set_street_name($name){
    $this->street_name = (string) $name;
  }

  private function send_error($message){
    throw new e_model($message);
  }

  public function add_processing_center(data_house2processing_center $center){
    if(array_key_exists($center->get_id(), $this->centers))
      $this->send_error('К дому уже привязан процессинговый центр.');
    $this->centers[$center->get_id()] = $center;
  }

  public function remove_processing_center(data_house2processing_center $center){
    if(!array_key_exists($center->get_id(), $this->centers))
      $this->send_error('Процессинговый центр не привязан к дому.');
    unset($this->centers[$center->get_id()]);
  }

  public function get_processing_centers(){
    return $this->centers;
  }
}