<?php

class model_report{

  public function __construct($name){
    if(!in_array($name, ['query'], true))
      throw new RuntimeException();
    // if(empty($_SESSION['report'][$name])){
      $this->init_default_params($name);
    // }
  }

  public function init_default_params($name){
    switch($name){
      case 'query':
        $_SESSION['report']['query']['time'] = time();
        $_SESSION['report']['query']['status'] = ['open', 'close', 'reopen', 'working'];
        $_SESSION['report']['query']['work_types'] = [];
        $_SESSION['report']['query']['streets'] = [];
        $_SESSION['report']['query']['houses'] = [];
      break;
    }
  }
}