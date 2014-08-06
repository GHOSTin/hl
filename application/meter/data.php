<?php

final class data_meter extends data_object{

  private $capacity;
  private $company_id;
  private $id;
  private $name;
  private $rates;
  private $periods = [];
  private $services = [];

  private static $service_list = ['cold_water', 'hot_water', 'electrical'];

  public function add_period($period){
    if($period < 0 OR $period > 241)
      throw new DomainException('Период задан не верно.');
    $period = $period;
    if(in_array($period, $this->periods, true))
      throw new DomainException('Такой период уже задан в счетчике.');
    $this->periods[] = $period;
  }

  public function add_service($service){
    if(!in_array($service, self::$service_list))
      throw new DomainException('Услуга задана не верно.');
    if(in_array($service, $this->services, true))
      throw new DomainException('Такая служба уже привязана к счетчику.');
    $this->services[] = $service;
  }

  public function remove_period($period){
    self::verify_period($period);
    $rs = array_search($period, $this->periods);
    if($rs === false)
      throw new DomainException('Периода не было в этом счетчике.');
    unset($this->periods[$rs]);
  }

  public function remove_service($service){
    self::verify_service($service);
    $rs = array_search($service, $this->services);
    if($rs === false)
      throw new DomainException('Службы не было в этом счетчике.');
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
    if($id > 16777215 OR $id < 1)
      throw new DomainException('Идентификатор счетчика задан не верно.');
    $this->id = (int) $id;
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
    if(!preg_match('/^[а-яА-Яa-zA-Z0-9 -]{1,20}$/u', $name))
      throw new DomainException('Название счетчика задано не верно.');
    $this->name = (string) $name;
  }

  public function set_capacity($capacity){
    if($capacity < 1 OR $capacity > 9)
      throw new DomainException('Разрядность задана не верно.');
    $this->capacity = $capacity;
  }

  public function set_rates($rates){
    if($rates < 1 OR $rates > 3)
      throw new DomainException('Тарифность задана не верно.');
    $this->rates = (int) $rates;
  }

  public function get_periods(){
    return $this->periods;
  }
}