<?php
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->exec("CREATE TABLE IF NOT EXISTS `metrics` (
  `id` varchar(128) NOT NULL,
  `address` varchar(255) NOT NULL,
  `metrics` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");