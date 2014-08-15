<?php

class factory_user{

  public function build(array $data){
    $user = new data_user();
    $user->set_id($data['id']);
    $user->set_login($data['login']);
    $user->set_status($data['status']);
    $user->set_firstname($data['firstname']);
    $user->set_middlename($data['middlename']);
    $user->set_lastname($data['lastname']);
    $user->set_company_id($data['company_id']);
    $user->set_hash($data['password']);
    $user->set_telephone($data['telephone']);
    $user->set_cellphone($data['cellphone']);
    return $user;
  }
}