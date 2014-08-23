<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/framework/framework.php");
require_once(ROOT."/vendor/autoload.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pimple = di::get_instance();
$pimple['company'] = di::get('mapper_company')->find(2);
di::get('mapper_company')->find_all();
di::get('mapper_user')->get_users();
di::get('mapper_number')->find_all();
di::get('mapper_query')->find_all();
di::get('mapper_street')->get_streets();
di::get('mapper_group')->get_groups();
di::get('mapper_workgroup')->get_workgroups();
di::get('mapper_department')->get_departments();
di::get('mapper_work')->find_all();