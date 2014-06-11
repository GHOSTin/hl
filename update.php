<?php
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->exec("
  CREATE TABLE IF NOT EXISTS `tasks` (
  `id` bigint(14) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time_open` int(16) NOT NULL,
  `time_close` int(16) NOT NULL,
  `time_target` int(16) DEFAULT NULL,
  `rating` int(5) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('open','close','reopen') DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");