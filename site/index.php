<?php
# подключаем фреймворк
$dir = dirname(__FILE__);
define('ROOT' , substr($dir, 0, (strlen($dir) - strlen('/site'))));
require_once(ROOT."/framework/framework.php");
require_once ROOT.'/libs/Twig/Autoloader.php';
Twig_Autoloader::register();	
# отделение статики от работы php-скрипта
if(!empty($_GET['js'])){
	get_component_js();
}elseif(!empty($_GET['css'])){
	get_component_css();
}else{
	model_environment::get_page_content();
}