<?php

class factory_number{

  public function build(array $data){
    $number = new data_number();
    $number->set_id($data['id']);
    $number->set_fio($data['fio']);
    $number->set_email($data['email']);
    $number->set_number($data['number']);
    $number->set_status($data['status']);
    $number->set_hash($data['password']);
    $number->set_telephone($data['telephone']);
    $number->set_cellphone($data['cellphone']);
    return $number;
  }
}