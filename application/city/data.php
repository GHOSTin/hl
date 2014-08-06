<?php
class data_city extends data_object{

	private $id;
  private $name;
	private $status;

  private static $status_list = ['false', 'true'];

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
      throw new DomainException('Идентификатор города задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[а-яА-Я ]{0,20}$/u', $name))
      throw new DomainException('Название города задано не верно.');
    $this->name = $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$status_list, true))
      throw new DomainException('Статус города задан не верно.');
    $this->status = $status;
  }
}