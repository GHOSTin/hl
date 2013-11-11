<?php
/*
* Связь с таблицей `streest`.
* Улицы глобальны, но пока привязаны к компании.
*/
final class data_street extends data_object{

    private $company_id;
    private $city_id;
    private $department_id;
    private $id;
    private $name;
    private $status;
    private $houses = [];

    public static $statuses = ['true', 'false'];

    public static function __callStatic($method, $args){
      if(!in_array($method, get_class_methods(verify_street), true))
        throw new e_model('Нет доступного метода.');
      return verify_street::$method($args[0]);
    }

    public function add_house(data_house $house){
      if(array_key_exists($house->get_id(), $this->houses))
        throw new e_model('Дом уже добавлен в улицу.');
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
      $this->id = (int) $id;
      self::verify_id($this->id);
    }

    public function set_name($name){
      $this->name = (string) $name;
      self::verify_name($this->name);
    }

    public function set_status($status){
      $this->status = (string) $status;
      self::verify_status($status);
    }
}