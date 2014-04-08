<?php
define('ROOT' , __DIR__);
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
    tarif decimal(9,3) NOT NULL,
    ind decimal(9,3) NOT NULL,
    odn decimal(9,3) NOT NULL,
    sum_ind decimal(9,3) NOT NULL,
    sum_odn decimal(9,3) NOT NULL,
    recalculation decimal(9,3) NOT NULL,
    facilities decimal(9,3) NOT NULL,
    total decimal(9,3) NOT NULL,
    KEY(company_id),
    KEY(number_id),
    KEY(time)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');