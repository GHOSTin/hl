<?php

class factory_house{

  public function build(array $data){
    $house = new data_house();
    $house->set_id($data['id']);
    $house->set_number($data['number']);
    $house->set_street($data['street']);
    return $house;
  }
}