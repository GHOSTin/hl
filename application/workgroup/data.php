<?php
class data_workgroup extends data_object{

  private $id;
  private $name;
	private $status;
  private $works = [];

  public static $statuses = ['active', 'deactive'];

  public static function __callStatic($method, $args){
    if(!in_array($method, get_class_methods('verify_workgroup'), true))
      throw new BadMethodCallException();
    return verify_workgroup::$method($args[0]);
  }

  public function add_work(data_work $work){
    if(array_key_exists($work->get_id(), $this->works))
      throw new e_model('Такая работа уже добавлена в группу.');
    $this->works[$work->get_id()] = $work;
  }

  public function get_works(){
    return $this->works;
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
    self::verify_status($this->status);
  }
}