<?php
http_router::build_route();
$file = ROOT.'/'.framework_configuration::application_folder.'/application_configuration.php';
if(file_exists($file))
	require_once $file;
else
	die('application_configuration not found');
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
		if(file_exists($file_path))
			require_once $file_path;
		else
			die('Class '.$class_name.' not found!');
  }else
    return;
}
spl_autoload_register('framework_autoload');
class twig{
	private static $instance;
	private static $twig;
	private function __construct(){}
	private function __clone(){} 
	private function __wakeup(){}
	public static function get_instance(){
        if(is_null(self::$instance)){
          self::$instance = new twig();
          if(is_null(self::$twig))
          	self::$instance->load_twig();
        }
        return self::$instance;
    }
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
function load_template($name, $args = []){
	return twig::get_instance()->load_template($name, $args);
}
class e_controller extends exception{}
class e_model extends exception{}
class e_view extends exception{}
class e_params extends exception{}