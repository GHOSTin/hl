<?php
/*
* Связь с таблицей `workgroups`.
* В каждой компании свои группы работы.
*/
class data_workgroup extends data_object{

	private $company_id;
  private $id;
  private $name;
	private $status;
  private $works = [];
  private static $statuses = ['active', 'deactive'];

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
    $id = (int) $id;
    self::verify_id($id);
    $this->id = $id;
  }

  public function set_name($name){
    self::verify_name($name);
    $this->name = $name;
  }

  public function set_status($status){
    self::verify_status($status);
    $this->status = $status;
  }

  /**
  * Верификация идентификатора группы работ.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор группы работ задан не верно.');
  }

  /**
  * Верификация названия группы работ.
  */
  public static function verify_name($name){
    // if(!preg_match('/^[А-Я][а-я ]{2,99}$/u', $name))
    //   throw new e_model('Название группы работ не удовлетворяет "а-яА-Я".');
  }

  /**
  * Верификация статуса группы работ.
  */
  public static function verify_status($status){
    if(!in_array($status, self::$statuses, true))
      throw new e_model('Статус группы работ задан не верно.');
  }
}