<?php
class model_resolver{

  public function get_controller(model_request $request){
    session_start();
    $path = parse_url($_SERVER['REQUEST_URI']);
    if($path['path'] === '/')
      $route = ['default_page', 'show_default_page'];
    elseif(preg_match_all('|^/([a-z_]+)/$|', $path['path'], $args, PREG_PATTERN_ORDER)){
      $route = [$args[1][0], 'show_default_page'];
    }elseif(preg_match_all('|^/([a-z_]+)/([a-z_]+)$|', $path['path'], $args, PREG_PATTERN_ORDER)){
      $route = [$args[1][0], $args[2][0]];
    }else
      $route = ['error', 'error404'];

    if(isset($_SESSION['user']) AND $_SESSION['user'] instanceof data_current_user)
      $prefix = 'private_';
    else
      $prefix = 'public_';
    $file = ROOT.'/application/'.$route[0].'/controller.php';
    if(file_exists($file))
      require_once $file;
    $class = 'controller_'.$route[0];
    $method = $prefix.$route[1];
    if(class_exists($class))
      if(method_exists($class, $method))
        return [$class, $method];
    return ['controller_error', 'error404'];
  }
}