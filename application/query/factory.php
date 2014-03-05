<?php

class factory_query{

  public function build(array $data){
    $query = new data_query();
    $query->set_id($data['id']);
    $query->set_status($data['status']);
    $query->set_initiator($data['initiator']);
    $query->set_payment_status($data['payment_status']);
    $query->set_warning_status($data['warning_status']);
    $query->set_close_reason($data['close_reason']);
    $query->set_time_open($data['time_open']);
    $query->set_time_work($data['time_work']);
    $query->set_time_close($data['time_close']);
    $query->set_description($data['description']);
    $query->set_number($data['number']);
    $query->set_contact_fio($data['contact_fio']);
    $query->set_contact_telephone($data['contact_telephone']);
    $query->set_contact_cellphone($data['contact_cellphone']);
    $query->set_department($data['department']);
    $query->set_house($data['house']);
    $query->add_work_type($data['type']);
    return $query;
  }
}