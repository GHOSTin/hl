<?php
/*
* Связь с таблицей `workgroups`.
* В каждой компании свои группы работы.
*/
final class data_workgroup extends data_object{

	private $company_id;
  private $id;
  private $name;
	private $status;
  private $works = [];

  public function add_work(data_work $work){
    if(array_key_exists($work->get_id(), $this->works))
      throw new e_model('Такая работа уже добавленав группу.');
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

  public function set_id($id){
    $this->id = (int) $id;
  }

  public function set_name($name){
    $this->name = (string) $name;
  }

  public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_workgroup::$value($this);
  }
}