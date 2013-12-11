<?php
define('ROOT' ,__DIR__);
require_once(ROOT."/framework/framework.php");
model_environment::create_batabase_connection();

$CREATE_TABLE_ERRORS = "CREATE TABLE IF NOT EXISTS `errors` (
  `time` INT(10) UNSIGNED NOT NULL,
  `user_id` SMALLINT(5) UNSIGNED NOT NULL,
  `text` MEDIUMTEXT NOT NULL,
  KEY `time` (`time`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$pdo = db::get_handler();
if($pdo->exec($CREATE_TABLE_ERRORS) === false)
  die('Таблица errors не была создана.');