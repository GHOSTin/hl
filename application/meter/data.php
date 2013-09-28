<?php
/*
* Связь с таблицей `meters`.
*/
final class data_meter extends data_object{
  
  private $capacity;
  private $company_id;
  private $id;
  private $name;
  private $rates;
  private $periods = [];
  private $services = [];
  private static $service_list = ['cold_water', 'hot_water', 'electrical'];

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_meter::$value($this);
  }

  public function add_period($period){
    if(in_array($period, $this->periods))
      throw new e_model('Такой период уже задан в счетчике.');
    $this->periods[] = (int) $period;
  }

  public function add_service($service){
    if(in_array($service, $this->services, true))
      throw new e_model('Такая служба уже привязана к счетчику.');
    $this->services[] = $service;
  }

  public function remove_period($period){
    $rs = array_search($period, $this->periods);
    if($rs === false)
      throw new e_model('Периода не было в этом счетчике.');
    unset($this->periods[$rs]);
  }

  public function remove_service($service){
    $rs = array_search($service, $this->services);
    if($rs === false)
      throw new e_model('Службы не было в этом счетчике.');
    unset($this->services[$rs]);
  }

  public function get_id(){
    return $this->id;
  }

  public function get_company_id(){
    return $this->company_id;
  }

  public function get_name(){
    return $this->name;
  }

  public function get_capacity(){
    return $this->capacity;
  }

  public function get_rates(){
    return $this->rates;
  }

  public function set_id($id){
    $this->id = (int) $id;
  }

  public function set_service($service){
    if(!in_array($service, self::$service_list, true))
      throw new e_model('Недопустимая служба');
    $this->service[] = (string) $service;
  }

  public function get_services(){
    return $this->services;
  }

  public function set_company_id($id){
    $this->company_id = $id;
  }

  public function set_name($name){
    $this->name = (string) $name;
  }

  public function set_capacity($capacity){
    $this->capacity = $capacity;
  }

  public function set_rates($rates){
    $this->rates = $rates;
  }

  public function get_periods(){
    return $this->periods;
  }
}