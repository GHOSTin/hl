<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->exec('
  CREATE TABLE IF NOT EXISTS accruals (
    company_id TINYINT(3) UNSIGNED NOT NULL,
    number_id MEDIUMINT(8) UNSIGNED NOT NULL,
    time INT(10) UNSIGNED NOT NULL,
    service VARCHAR(255) NOT NULL,
    unit VARCHAR(16) NOT NULL,
    tarif VARCHAR(16) NOT NULL,
    ind VARCHAR(16) NOT NULL,
    odn VARCHAR(16) NOT NULL,
    sum_ind VARCHAR(16) NOT NULL,
    sum_odn VARCHAR(16) NOT NULL,
    sum_total VARCHAR(16) NOT NULL,
    facilities VARCHAR(16) NOT NULL,
    recalculation VARCHAR(16) NOT NULL,
    total VARCHAR(16) NOT NULL,
    KEY(company_id),
    KEY(number_id),
    KEY(time)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');
