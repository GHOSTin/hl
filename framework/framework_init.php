<?php
http_router::build_route();
$file = ROOT.'/'.framework_configuration::application_folder.'/application_configuration.php';
if(file_exists($file)){
	require_once $file;
}else{
	class application_configuration{}
}

# автозагрузка классов
function __autoload($class_name){
	list($folder, $component) = explode('_', $class_name, 2);
	$file_path = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/'.$folder.'.php';
	if(file_exists($file_path)){
		require_once $file_path;
	}else{
		die($class_name);
	}
}
/*
function gh(){

	die('ERROR!');
}
set_error_handler(gh());
*/

function load_template($path, $args = null){
	list($component, $template_file) = explode('.', $path);
	$file_path = ROOT.'/'.framework_configuration::application_folder.'/'.$component.'/templates/'.$template_file.'.php';
	if(file_exists($file_path)){
		require_once($file_path);
	}else{
		die('Template not be load!');
	}
}
