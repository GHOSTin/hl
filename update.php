<?php
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->exec("CREATE TABLE `sessions_logs`(
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `time` INT(10) UNSIGNED NOT NULL,
  `ip` VARCHAR(15) NOT NULL,
  KEY(`user_id`)
  )Engine=InnoDB DEFAULT CHARSET=utf8");
var_dump($pdo);