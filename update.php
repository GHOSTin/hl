<?php
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->exec("CREATE TABLE IF NOT EXISTS `task2comment` (
  `task_id` bigint(14) NOT NULL,
  `user_id` smallint(5) NOT NULL,
  `message` text NOT NULL,
  `time` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");