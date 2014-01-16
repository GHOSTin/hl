<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__))));
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
$pdo = di::get('pdo');
$stmt = $pdo->prepare('CREATE TABLE `query2comment`(
  `company_id` TINYINT(3) UNSIGNED NOT NULL,
  `query_id` INT(10) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `time` INT(10) UNSIGNED NOT NULL,
  `message` MEDIUMTEXT NOT NULL,
  KEY `query` (`company_id`, `query_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
$stmt->execute();