<?php

class factory_street{

  public function create(array $row){
    $street = new data_street();
    $street->set_id($row['id']);
    $street->set_name($row['name']);
    return $street;
  }
}