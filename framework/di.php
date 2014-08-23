<?php
class di{

  private static $instance;


  public static function get_instance(){
    return self::$instance;
  }

  public static function set_instance($instance){
    return self::$instance = $instance;
  }

  /**
   * @param string $key
   * @return object $key()
   */
  public static function get($key){
    $pimple = self::get_instance();
    return $pimple[$key];
  }
}