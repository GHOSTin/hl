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
// подгрузка временной реализации класс шаблонизатора
try{
	require_once('twig.php');
	require_once ROOT.'/libs/Twig/Autoloader.php';
	Twig_Autoloader::register();
}catch(exception $e){
	die('Шаблонизатор не может быть подгружен.');
}

function stm_map_result(PDOStatement $stm, data_object $data_object, $error){
	stm_execute($stm, $error);
	$stm->setFetchMode(PDO::FETCH_CLASS, get_class($data_object));
	$result = [];
	while($object = $stm->fetch())
		$result[] = $object;
	$stm->closeCursor();
	return $result;
}
function stm_execute(PDOStatement $stm, $error){
	if(empty($error))
		throw new exception('Задайте вспомогательную фразу.');
	if($stm->execute() == false)
		throw new e_model($value);
}