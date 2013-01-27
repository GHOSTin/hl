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

function load_template111($path, $args = null){
	list($component, $template_file) = explode('.', $path);
	$file_path = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/templates/'.$template_file.'.tpl';
	if(file_exists($file_path)){

		#require_once($file_path);
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader);

		$template = $twig->loadTemplate($file_path);
		$template->display(array());
		
		exit();

	}else{
		die('Template not be load!');
	}
}
/*
function gh(){

	die('ERROR!');
}
set_error_handler(gh());
*/