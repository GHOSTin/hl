<?php

class factory_flat{

  public function create(array $row){
    $flat = new data_flat();
    $flat->set_id($row['id']);
    $flat->set_number($row['number']);
    return $flat;
  }
}