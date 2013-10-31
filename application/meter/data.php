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

  public function __construct($id = null){
    if(!is_null($id))
      $this->set_id($id);
  }

  public function add_period($period){
    $period = (int) $period;
    self::verify_period($period);
    if(in_array($period, $this->periods, true))
      throw new e_model('Такой период уже задан в счетчике.');
    $this->periods[] = $period;
  }

  public function add_service($service){
    self::verify_service($service);
    if(in_array($service, $this->services, true))
      throw new e_model('Такая служба уже привязана к счетчику.');
    $this->services[] = $service;
  }

  public function remove_period($period){
    self::verify_period($period);
    $rs = array_search($period, $this->periods);
    if($rs === false)
      throw new e_model('Периода не было в этом счетчике.');
    unset($this->periods[$rs]);
  }

  public function remove_service($service){
    self::verify_service($service);
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

  public static function get_service_list(){
    return self::$service_list;
  }

  public function set_id($id){
    $id = (int) $id;
    self::verify_id($id);
    $this->id = $id;
  }

  public function set_service($service){
    self::verify_service($service);
    $this->services[] = $service;
  }

  public function get_services(){
    return $this->services;
  }

  public function set_company_id($id){
    $this->company_id = $id;
  }

  public function set_name($name){
    self::verify_name($name);
    $this->name = $name;
  }

  public function set_capacity($capacity){
    $capacity = (int) $capacity;
    self::verify_capacity($capacity);
    $this->capacity = $capacity;
  }

  public function set_rates($rates){
    $rates = (int) $rates;
    self::verify_rates($rates);
    $this->rates = $rates;
  }

  public function get_periods(){
    return $this->periods;
  }

  public static function verify_capacity($capacity){
    if($capacity < 1 OR $capacity > 9)
      throw new e_model('Разрядность задана не верно.');
  }

  public static function verify_id($id){
    if($id > 16777215 OR $id < 1)
      throw new e_model('Идентификатор счетчика задан не верно.');
  }

  public static function verify_name($name){
    if(!preg_match('/^[а-яА-Яa-zA-Z0-9 -]{1,20}$/u', $name))
      throw new e_model('Название счетчика задано не верно.');
  }

  public static function verify_period($period){
    if($period < 0 OR $period > 240)
        throw new e_model('Период задан не верно.');
  }

  public static function verify_rates($rates){
    if($rates < 1 OR $rates > 3)
      throw new e_model('Тарифность задана не верно.');
  }

  public static function verify_service($service){
    if(!in_array($service, self::$service_list))
      throw new e_model('Услуга задана не верно.');
  }
}