<?php
define('ROOT' ,__DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);

$pdo = di::get('pdo');
$stmt = $pdo->exec('CREATE TABLE `client_queries` (
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `number_id` MEDIUMINT(8) NOT NULL,
  `time` INT(10) UNSIGNED NOT NULL,
  `status` enum("new", "working", "aborted") NOT NULL,
  `text` VARCHAR(255),
  `reason` VARCHAR(255),
  `query_id` INT(10) UNSIGNED NOT NULL,
  UNIQUE KEY `id` (`company_id`, `number_id`, `time`),
  KEY (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');