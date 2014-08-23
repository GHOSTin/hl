<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/site'))));
require_once(ROOT."/framework/framework.php");
require_once(ROOT."/vendor/autoload.php");
print(model_environment::get_page_content());