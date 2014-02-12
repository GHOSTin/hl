<?php

class factory_client_query{

  public function build(array $data){
    $client_query = new data_client_query();
    $client_query->set_time($data['time']);
    $client_query->set_text($data['text']);
    $client_query->set_status($data['status']);
    if(!empty($data['reason']))
      $client_query->set_reason($data['reason']);
    if(!empty($data['query_id']))
      $client_query->set_query_id($data['query_id']);
    return $client_query;
  }
}