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

    public function add_house(data_house $house){
      if(array_key_exists($house->get_id(), $this->houses))
        throw new e_model('Дом уже добавлен в улицу.');
      $this->houses[$house->get_id()] = $house;
    }

    public function get_houses(){
      return $this->houses;
    }

    public function get_company_id(){
      return $this->company_id;
    }

    public function get_city_id(){
      return $this->city_id;
    }

    public function get_department_id(){
      return $this->department_id;
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

    public function set_company_id($id){
      $this->company_id = (int) $id;
    }

    public function set_city_id($id){
      $this->city_id = (int) $id;
    }

    public function set_department_id($id){
      $this->department_id = (int) $id;
    }

    public function set_id($id){
      $this->id = (int) $id;
    }

    public function set_name($name){
      $this->name = (string) $name;
    }

    public function set_status($status){
      $this->status = (string) $status;
    }

    public function verify(){
        if(func_num_args() < 0)
            throw new e_data('Параметры верификации не были переданы.');
        foreach(func_get_args() as $value)
            verify_street::$value($this);
    }
}