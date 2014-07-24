<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->exec('
  CREATE TABLE IF NOT EXISTS statements (
    company_id TINYINT(3) UNSIGNED NOT NULL,
    number_id MEDIUMINT(8) UNSIGNED NOT NULL,
    time INT(10) UNSIGNED NOT NULL,
    service VARCHAR(16) NOT NULL,
    meter VARCHAR(16) NOT NULL,
    tarif VARCHAR(16) NOT NULL,
    registration VARCHAR(16) NOT NULL,
    day decimal(9,3),
    night decimal(9,3),
    KEY(company_id),
    KEY(number_id),
    KEY(time)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');
