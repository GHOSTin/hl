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

class component_session_manager{

	public $component;
	public $storage;

	public function __construct($storage, $component){
		if(strlen($component) < 1)
			throw new e_model('Проблема в сесси компонента.');
		$this->component = $component;
		$this->storage = $storage;
	}

	public function set($key, $value){
		$this->storage->set($this, $key, $value);
	}

	public function get($key){
		return $this->storage->get($this, $key);
	}
}

class php_session_storage{

	public function set($manager, $key, $value){
		$_SESSION['components'][$manager->component][$key] = $value;
	}

	public function get($manager, $key){
		return $_SESSION['components'][$manager->component][$key];
	}
}

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

$pimple = di::get_instance();

$pimple['pdo'] = $pimple->share(function($pimple){
	$pdo = new PDO('mysql:host='.application_configuration::database_host.';dbname='.application_configuration::database_name, application_configuration::database_user, application_configuration::database_password);
	$pdo->exec("SET NAMES utf8");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  return $pdo;
});

require_once ROOT.'/libs/Twig/Autoloader.php';
Twig_Autoloader::register();

$pimple['twig'] = $pimple->share(function($pimple){
	$options = [];
	$loader = new Twig_Loader_Filesystem(ROOT.'/templates/');
	return new Twig_Environment($loader, $options);
});

function load_template($name, array $args = []){
  $twig = di::get_instance()['twig'];
  list($component, $template) = explode('.', $name, 2);
  $template_dir = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/templates/';
  $loader = $twig->getLoader();
  $loader->prependPath($template_dir, $component);
  return $twig->render('@'.$component.'/'.$template.'.tpl', $args);
}