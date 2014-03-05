<?php

class factory_department{

  public function build(array $data){
    $department = di::get('data_department');
    $department->set_id($data['id']);
    $department->set_name($data['name']);
    return $department;
  }
}