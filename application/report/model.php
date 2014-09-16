<?php

class model_report{

  public function __construct(){
    if(empty($_SESSION['report_query'])){
      $this->init_default_params();
    }
  }

  public function init_default_params(){
    $time = getdate();
    $_SESSION['report_query']['time_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
    $_SESSION['report_query']['time_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
    $_SESSION['report_query']['status'] = ['open', 'close', 'reopen', 'working'];
    $_SESSION['report_query']['work_types'] = [];
    $_SESSION['report_query']['streets'] = [];
    $_SESSION['report_query']['houses'] = [];
    $_SESSION['report_query']['departments'] = [];
  }

  public function set_time_begin($time){
    $_SESSION['report_query']['time_begin'] = $time;
  }

  public function set_time_end($time){
    $_SESSION['report_query']['time_end'] = $time;
  }

  public function set_status($status){
    if(in_array($status, ['open', 'close', 'reopen', 'working'], true))
      $_SESSION['report_query']['status'] = [$status];
    else
      $_SESSION['report_query']['status'] = ['open', 'close', 'reopen', 'working'];
  }

  public function set_worktype($id){
    $wt = di::get('em')->find('data_query_work_type', $id);
    if(is_null($wt)){
      $_SESSION['report_query']['work_types'] = [];
    }else{
      $_SESSION['report_query']['work_types'] = [$wt->get_id()];
    }
  }

  public function set_department($id){
    $department = di::get('em')->find('data_department', $id);
    if(is_null($department)){
      $_SESSION['report_query']['departments'] = [];
    }else{
      $_SESSION['report_query']['departments'] = [$department->get_id()];
    }
    $_SESSION['report_query']['streets'] = [];
    $_SESSION['report_query']['houses'] = [];
  }

  public function set_street($id){
    $em = di::get('em');
    $streets = $em->getRepository('data_street')->findAll();
    $s = [];
    if(!empty($streets))
      foreach($streets as $street){
          $s[] = $street->get_id();
      }
    if(!in_array((int) $id, $s, true)){
      $_SESSION['report_query']['departments'] = [];
      $_SESSION['report_query']['streets'] = [];
      $_SESSION['report_query']['houses'] = [];
    }else{
      $houses = $em->getRepository('data_house')->findByStreet($id);
      $h = [];
      if(!empty($houses))
        foreach($houses as $house)
          $h[] = $house->get_id();
      $_SESSION['report_query']['departments'] = [];
      $_SESSION['report_query']['streets'] = [$id];
      $_SESSION['report_query']['houses'] = $h;
    }
  }

  public function set_house($id){
    if($id > 0){
      $house = di::get('em')->find('data_house', $id);
      if(is_null($house))
        $_SESSION['report_query']['houses'] = [];
      else
        $_SESSION['report_query']['houses'] = [$house->get_id()];
    }else
      $_SESSION['report_query']['houses'] = [];
  }

  public function get_queries(){
    return di::get('em')->getRepository('data_query')
      ->findByParams($_SESSION['report_query']);
  }

  public function get_filters(){
    $filters['time_open_begin'] = $_SESSION['report_query']['time_begin'];
    $filters['time_open_end'] = $_SESSION['report_query']['time_end'];
    $filters['department'] = $_SESSION['report_query']['departments'];
    if(count($_SESSION['report_query']['status']) === 1)
      $filters['status'] = $_SESSION['report_query']['status'][0];
    if(count($_SESSION['report_query']['work_types']) === 1)
      $filters['work_type'] = $_SESSION['report_query']['work_types'][0];
    if(count($_SESSION['report_query']['streets']) === 1)
      $filters['street'] = $_SESSION['report_query']['streets'][0];
    if(count($_SESSION['report_query']['houses']) === 1)
      $filters['house'] = $_SESSION['report_query']['houses'][0];
    return $filters;
  }
}