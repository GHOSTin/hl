<?php
function framework_autoload($class_name){
	if(
		(0 === strpos($class_name, 'model_'))
	 	OR (0 === strpos($class_name, 'view_'))
	 	OR (0 === strpos($class_name, 'controller_'))
	 	OR (0 === strpos($class_name, 'data_'))
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