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

  private static $statuses = ['false', 'true'];

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
    if($id > 65535 OR $id < 1)
      throw new DomainException('Идентификатор группы задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!preg_match('/^[0-9а-яА-Я ]{1,50}$/u', $name))
      throw new DomainException('Название группы задано не верно.');
    $this->name = $name;
  }

  public function set_status($status){
    if(!in_array($status, self::$statuses))
      throw new DomainException('Статус группы задан не верно.');
    $this->status = (string) $status;
  }
}