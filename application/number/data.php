<?php
/*
* Связь с таблицей `numbers`.
* Лицевые счета ассоциированы с компанией и с городом.
* В одном городе у одной компании не может быть два одинаковых лицевых счета.
*/
final class data_number extends data_object{

  private $cellphone;
  private $centers = [];
  private $city;
  private $fio;
  private $flat;
  private $hash;
  private $house;
  private $id;
  private $meters = [];
  private $number;
  private $status;
  private $telephone;
  private $type;

  public function __construct($id = null){
    $this->id = (int) $id;
  }

  public function add_meter(data_number2meter $n2m){
    $id = $n2m->get_meter()->get_id().'_'.$n2m->get_serial();
    if(array_key_exists($id, $this->meters))
      throw new e_model('Счетчик уже добавлен.');
    $this->meters[$id] = $n2m;
  }

  public function add_processing_center(data_number2processing_center $n2c){
    if(array_key_exists($n2c->get_id(), $this->centers))
      throw new e_model('Центр уже добавлен.');
    $this->centers[$n2c->get_id()] = $n2c;
  }

  public function delete_processing_center(data_number2processing_center $n2c){
    if(!array_key_exists($n2c->get_center()->get_id(), $this->centers))
      throw new e_model('Центр не привязан к лицевому счету.');
    unset($this->centers[$n2c->get_center()->get_id()]);
  }

  public function get_cellphone(){
    return $this->cellphone;
  }

  public function get_fio(){
    return $this->fio;
  }

  public function get_flat(){
    return $this->flat;
  }

  public function get_hash(){
    return $this->hash;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_meters(){
    return $this->meters;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_processing_centers(){
    return $this->centers;
  }

  public function get_telephone(){
    return $this->telephone;
  }

  public function remove_n2m(data_number2meter $n2m){
    $id = $n2m->get_meter()->get_id().'_'.$n2m->get_serial();
    if(!array_key_exists($id, $this->meters))
      throw new e_model('Счетчик не привязан к лицевому счету.');
    unset($this->meters[$id]);
  }

  public function set_cellphone($cellphone){
  	$this->cellphone = (string) $cellphone;
  }

  public function set_fio($fio){
  	$this->fio = (string) $fio;
  }

  public function set_flat(data_flat $flat){
      $this->flat = $flat;
  }

  public function set_hash($hash){
    $this->hash = (string) $hash;
  }

  public function set_id($id){
    $this->id = (int) $id;
  }

  public function set_city(data_city $city){
    $this->city = $city;
  }

  public function set_number($number){
    $this->number = (string) $number;
  }

  public function set_status($status){
    $this->status = (string) $status;
  }

  public function set_telephone($telephone){
  	$this->telephone = (string) $telephone;
  }

  public function set_type($type){
    $this->type = (string) $type;
  }

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_number::$value($this);
  }
}