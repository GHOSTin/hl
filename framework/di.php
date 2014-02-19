<?php

require_once(ROOT.'/libs/Pimple.php');

class di{

  private static $instance;

  private function __construct(){}
  private function __clone(){}
  private function __wakeup(){}

  public static function get_instance(){
    if(is_null(self::$instance))
      self::$instance = new Pimple();
    return self::$instance;
  }

  public static function get($key){
    $pimple = self::get_instance();
    return $pimple[$key];
  }
}