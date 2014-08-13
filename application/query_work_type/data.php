<?php

class data_query_work_type extends data_object{

  private $id;
  private $name;

  public function get_id(){
    return $this->id;
  }

  public function get_name(){
    return $this->name;
  }

  public function set_id($id){
    if($id > 255 OR $id < 1)
      throw new e_model('Идентификатор типа заявки задан не верно.');
    $this->id = $id;
  }

  public function set_name($name){
    if(!in_array($query_work_type->status, ['active', 'deactive']))
      throw new e_model('Статус типа работ задан не верно.');
    $this->name = $name;
  }
}