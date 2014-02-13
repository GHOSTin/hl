<?php

class model_client_query{

  private $company;

  public function __construct(data_company $company){
    $this->company = $company;
  }

  public function cancel_client_query($number_id, $time, $reason){
    $mapper = new mapper_client_query(di::get('pdo'));
    $query = $mapper->find($this->company, $number_id, $time);
    $query->set_status('canceled');
    $query->set_reason($reason);
    $mapper->update($query);
  }

  public function accept_client_query(data_query $query, $number_id, $time){

    $mapper = new mapper_client_query(di::get('pdo'));
    $client_query = $mapper->find($this->company, $number_id, $time);
    $client_query->set_status('accepted');
    $client_query->set_query_id($query->get_id());
    $mapper->update($client_query);
  }
}