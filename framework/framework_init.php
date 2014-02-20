<?php
http_router::build_route();
// проверка на существования конфигурации приложения
$file = ROOT.'/'.framework_configuration::application_folder.'/application_configuration.php';
if(file_exists($file))
	require_once $file;
else
	die('application_configuration not found');
// автозагрузка классов
function framework_autoload($class_name){
	if(
		(0 === strpos($class_name, 'model_')) 
	 	OR (0 === strpos($class_name, 'view_')) 
	 	OR (0 === strpos($class_name, 'controller_'))
	 	OR (0 === strpos($class_name, 'data_'))
	 	OR (0 === strpos($class_name, 'verify_'))
	 	OR (0 === strpos($class_name, 'mapper_'))
	 	OR (0 === strpos($class_name, 'collection_'))
	 	OR (0 === strpos($class_name, 'factory_'))
	){
		list($folder, $component) = explode('_', $class_name, 2);
		$file_path = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/'.$folder.'.php';
		if(file_exists($file_path))
			require_once $file_path;
	}else
		return;
}
spl_autoload_register('framework_autoload');

class mem{

	private $name;

	public function __construct($name){
		$this->name = (string) $name;
	}

	public function get_params(){
		return $_SESSION['model'][$this->name];
	}

	public function save(array $params){
		$_SESSION['model'][$this->name] = $params;
	}
}
require_once ROOT.'/libs/Twig/Autoloader.php';
Twig_Autoloader::register();


function load_template($name, array $args = []){
  $twig = di::get_instance()['twig'];
  list($component, $template) = explode('.', $name, 2);
  $template_dir = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/templates/';
  $loader = $twig->getLoader();
  $loader->prependPath($template_dir, $component);
  return $twig->render('@'.$component.'/'.$template.'.tpl', $args);
}