<?php
/*
* Связь с таблицей `houses`.
* Дома глобальны, но пока привязаны к компании.
*/
final class data_house extends data_object{
	
	private $city_id;
  private $city_name;
	private $company_id;
	private $department_id;
	private $id;
	private $number;
	private $status;
	private $street_id;
	private $street_name;
  private $centers = [];
  private $numbers = [];

  public function add_number(data_number $number){
    if(array_key_exists($number->get_id(), $this->numbers))
      throw new e_model('Дом уже добавлен в улицу.');
    $this->numbers[$number->get_id()] = $number;
  }

  public function get_city_id(){
    return $this->city_id;
  }

  public function get_city_name(){
    return $this->city_name;
  }

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_department_id(){
    return $this->department_id;
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

  public function set_city_id($id){
    $this->city_id = (int) $id;
  }

  public function set_city_name($name){
    $this->city_name = (string) $name;
  }

  public function set_company_id($id){
    $this->company_id = (int) $id;
  }

  public function set_department_id($id){
    $this->department_id = (int) $department_id;
  }

  public function set_id($id){
    $this->id = $id;
  }

  public function set_number($number){
    $this->number = (string) $number;
  }

  public function set_status($status){
    $this->status = (string) $status;
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

  public function add_processing_center(data_processing_center $center, $identifier){
    if(array_key_exists($center->get_id(), $this->centers))
      $this->send_error('К дому уже привязан процессинговый центр.');
    $this->centers[$center->get_id()] = [$center, $identifier];
  }

  public function remove_processing_center(data_processing_center $center){
    if(!array_key_exists($center->id, $this->centers))
      $this->send_error('Процессинговый центр не привязан к дому.');
    unset($this->centers[$center->id]);
  }

  public function get_processing_centers(){
    return $this->centers;
  }

	public function verify(){
    if(func_num_args() < 0)
      $this->send_error('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
        verify_house::$value($this);
  }
}