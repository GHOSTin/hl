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

  public function add_user(data_user $user){
    if(in_array($user->get_id(), $this->users, true))
      throw new e_model('Пользователь уже добавлен в группу.');
    $this->users[$user->get_id()] = $user;
  }

  public function get_company_id(){
    return $this->company_id;
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

  public function set_company_id($id){
    $this->company_id = (int) $id;
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
      verify_group::$value($this);
  }
}