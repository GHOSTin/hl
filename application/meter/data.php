<?php

final class data_meter extends data_object{
  
  private $capacity;
  private $company_id;
  private $id;
  private $name;
  private $rates;
  private $periods = [];
  private $services = [];
  
  public static $service_list = ['cold_water', 'hot_water', 'electrical'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods('verify_meter'), true))
      throw new e_model('Нет доступного метода.');
    return verify_meter::$method($args[0]);
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
    $this->id = (int) $id;
    self::verify_id($this->id);
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
    $this->name = (string) $name;
    self::verify_name($name);
  }

  public function set_capacity($capacity){
    $this->capacity = (int) $capacity;
    self::verify_capacity($this->capacity);
  }

  public function set_rates($rates){
    $this->rates = (int) $rates;
    self::verify_rates($this->rates);
  }

  public function get_periods(){
    return $this->periods;
  }
}