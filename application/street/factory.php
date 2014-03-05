<?php

class factory_street{

  public function build(array $row){
    $street = new data_street();
    $street->set_id($row['id']);
    $street->set_name($row['name']);
    return $street;
  }
}