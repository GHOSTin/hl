<?php

class model_report{

  public function __construct(){
    if(empty($_SESSION['report_query'])){
      $this->init_default_params();
    }
  }

  public function init_default_params(){
    $time = getdate();
    switch($name){
      case 'query':
        $_SESSION['report_query']['time_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
        $_SESSION['report_query']['time_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
        $_SESSION['report_query']['status'] = ['open', 'close', 'reopen', 'working'];
        $_SESSION['report_query']['work_types'] = [];
        $_SESSION['report_query']['streets'] = [];
        $_SESSION['report_query']['houses'] = [];
      break;
    }
  }

  public function set_time_begin($time){
    $_SESSION['report_query']['time_begin'] = $time;
  }

  public function set_time_end($time){
    $_SESSION['report_query']['time_end'] = $time;
  }

  public function get_queries(){
    return di::get('em')->getRepository('data_query')
      ->findByParams($_SESSION['report_query']);
  }

  public function get_filters(){
    $filters['time_open_begin'] = $_SESSION['report_query']['time_begin'];
    $filters['time_open_end'] = $_SESSION['report_query']['time_end'];
    return $filters;
  }
}