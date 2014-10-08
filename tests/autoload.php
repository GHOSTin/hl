<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/tests'))));
date_default_timezone_set('Asia/Yekaterinburg');
require_once(ROOT."/vendor/autoload.php");
require_once(ROOT.'/application/application_configuration.php');

class di{

  private static $instance;


  public static function get_instance(){
    return self::$instance;
  }

  public static function set_instance($instance){
    return self::$instance = $instance;
  }

  public static function get($key){
    $pimple = self::get_instance();
    return $pimple[$key];
  }
}