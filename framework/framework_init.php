<?php
http_router::build_route();
$file = ROOT.'/'.framework_configuration::application_folder.'/application_configuration.php';
if(file_exists($file)){
	require_once $file;
}else{
	class application_configuration{}
}

# автозагрузка классов

function framework_autoload($class_name){
	if(
		(0 === strpos($class_name, 'model_')) 
	 	OR (0 === strpos($class_name, 'view_')) 
	 	OR (0 === strpos($class_name, 'controller_'))
	 	OR (0 === strpos($class_name, 'data_'))
	){
		list($folder, $component) = explode('_', $class_name, 2);
		$file_path = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/'.$folder.'.php';
		if(file_exists($file_path)){
			require_once $file_path;
		}else{
			die('Class '.$class_name.' not found!');
		}	
    }else{
      	return;
    }
}
spl_autoload_register('framework_autoload');

class twig{

	private static $instance;
	private static $twig;

	private function __construct(){}
	private function __clone(){} 
	private function __wakeup(){}

	public static function get_instance(){    // Возвращает единственный экземпляр класса. @return Singleton
        if(is_null(self::$instance)){
            self::$instance = new twig();
            if(is_null(self::$twig))
            	self::$instance->load_twig();
        }
        return self::$instance;
    }
    //['cache' => ROOT.'/cache']
    public function load_twig(){
		$loader = new Twig_Loader_Filesystem(ROOT.'/templates/');
		self::$twig = new Twig_Environment($loader);
    }

    public function get_twig(){

    	return self::$twig;
    }

    public function load_template($name, $args = []){
    	list($component, $template) = explode('.', $name, 2);
    	$template_dir = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/templates/';
    	$loader = self::get_instance()->get_twig()->getLoader();
    	$loader->prependPath($template_dir, $component);
    	return self::get_instance()->get_twig()->render('@'.$component.'/'.$template.'.tpl', $args);
    }
}

 // Применение

function load_template($name, $args = []){

	return twig::get_instance()->load_template($name, $args);
}
/*
function catch_fatal_error()
{
  // Getting Last Error
  $last_error =  error_get_last();
  
  // Check if Last error is of type FATAL
  if(isset($last_error['type']) &amp;&amp; $last_error['type']==E_ERROR)
  {  
    // Fatal Error Occurs
    // Do whatever you want for FATAL Errors
  }

}
register_shutdown_function('catch_fatal_error');
file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);  
*/