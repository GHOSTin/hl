<?php

class factory_work{

  public function create(array $row){
    $work = new data_work();
    $work->set_id($row['id']);
    $work->set_name($row['name']);
    return $work;
  }
}