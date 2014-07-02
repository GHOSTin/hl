<?php
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->exec("ALTER TABLE `tasks` ADD `title` TEXT NOT NULL AFTER `id`");