<?php

class factory_number{

  private static $numbers = [];

  public function build(array $data){
    if(isset(self::$numbers[$data['id']]))
      return self::$numbers[$data['id']];
    $number = new data_number();
    $number->set_id($data['id']);
    $number->set_fio($data['fio']);
    $number->set_email($data['email']);
    $number->set_number($data['number']);
    $number->set_status($data['status']);
    $number->set_hash($data['password']);
    $number->set_telephone($data['telephone']);
    $number->set_cellphone($data['cellphone']);
    self::$numbers[$data['id']] = $number;
    return $number;
  }
}