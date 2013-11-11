<?php
/*
* Связь с таблицей `groups`.
* Группы уникальны в каждой компании.
*/
class data_group extends data_object{

	private $company_id;
  private $id;
  private $name;
  private $status;
  private $users = [];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods(verify_group), true))
      throw new e_model('Нет доступного метода.');
    return verify_group::$method($args[0]);
  }

  public function add_user(data_user $user){
    if(array_key_exists($user->get_id(), $this->users))
      throw new e_model('Пользователь уже добавлен в группу.');
    $this->users[$user->get_id()] = $user;
  }

  public function exclude_user(data_user $user){
    if(!array_key_exists($user->get_id(), $this->users))
      throw new e_model('Пользователя нет в группе.');
    unset($this->users[$user->get_id()]);
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

  public function get_users(){
    return $this->users;
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
    self::verify_status($this->status);
  }
}