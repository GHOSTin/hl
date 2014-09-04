<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/tests'))));
date_default_timezone_set('Asia/Yekaterinburg');
require_once(ROOT.'/application/application_configuration.php');

class pdo_mock extends PDO{

  public function __construct(){

  }
}
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

function framework_autoload($class_name){
  if(
    (0 === strpos($class_name, 'model_'))
    OR (0 === strpos($class_name, 'controller_'))
    OR (0 === strpos($class_name, 'data_'))
    OR (0 === strpos($class_name, 'repository_'))
  ){
    list($folder, $component) = explode('_', $class_name, 2);
    $file_path = ROOT.'/application/'.$component.'/'.$folder.'.php';
    if(file_exists($file_path))
      require_once $file_path;
  }else
    return;
}
spl_autoload_register('framework_autoload');
require_once(ROOT."/vendor/autoload.php");