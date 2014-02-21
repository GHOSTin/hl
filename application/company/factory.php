<?php
class factory_company{

  public function build(array $data){
    $company = new data_company();
    $company->set_id($data['id']);
    $company->set_name($data['name']);
    return $company;
  }
}