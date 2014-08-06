<?php

class data_street extends data_object{

  private $company_id;
  private $city_id;
  private $department_id;
  private $id;
  private $name;
  private $status;
  private $houses = [];

  public static $statuses = ['true', 'false'];

  public function add_house(data_house $house){
    if(array_key_exists($house->get_id(), $this->houses))
      throw new DomainException('Дом уже добавлен в улицу.');
    $this->houses[$house->get_id()] = $house;
  }

  public function get_houses(){
    return $this->houses;
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

  public function set_id($id){
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор улицы задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[0-9а-яА-Я-_ ]{3,20}$/u', $name))
      throw new DomainException('Название улицы задано не верно.');
    $this->name = $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses))
      throw new DomainException('Статус улицы задан не верно.');
    $this->status = $status;
  }
}