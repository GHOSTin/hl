<?php
class data_query2user{

  private $user;
  private $class;

  public function __call($method, $args){
    return $this->user->$method($args);
  }

  public function __construct(data_user $user){
    $this->user = $user;
    $this->user->verify('id');
  }

  public function set_class($class){
    if(!in_array($class, ['creator', 'observer', 'manager', 'performer'], true))
      throw new e_model('Не соответсвует тип пользователя.');
    $this->class = (string) $class;
  }

  public function get_class(){
    return $this->class;
  }
}