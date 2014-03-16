<?php

class factory_session{

  public function build(array $data){
    $session = new data_session();
    $session->set_user($data['user']);
    $session->set_time($data['time']);
    $session->set_ip($data['ip']);
    return $session;
  }
}