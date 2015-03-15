<?php namespace main\models;

use Silex\Application;

class report_event{

  public function __construct(Application $app){
    $this->app = $app;
    if(empty($_SESSION['report_event'])){
      $this->init_default_params();
    }
  }

  public function init_default_params(){
    $time = getdate();
    $_SESSION['report_event']['time_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
    $_SESSION['report_event']['time_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
  }

  public function set_time_begin($time){
    $_SESSION['report_event']['time_begin'] = $time;
  }

  public function set_time_end($time){
    $_SESSION['report_event']['time_end'] = $time;
  }

  public function get_events(){
    return $this->app['em']->getRepository('domain\number2event')->findByParams($_SESSION['report_event']);
  }

  public function get_filters(){
    $filters['time_begin'] = $_SESSION['report_event']['time_begin'];
    $filters['time_end'] = $_SESSION['report_event']['time_end'];
    return $filters;
  }
}