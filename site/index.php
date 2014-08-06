<?php
# подключаем фреймворк
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/site'))));
require_once(ROOT."/framework/framework.php");
require_once(ROOT."/vendor/autoload.php");
date_default_timezone_set(application_configuration::php_timezone);
# отделение статики от работы php-скрипта
if(!empty($_GET['js']))
	get_component_js();
elseif(!empty($_GET['css']))
	get_component_css();
else
	print(model_environment::get_page_content());