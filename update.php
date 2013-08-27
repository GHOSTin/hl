<?php
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");
model_environment::create_batabase_connection();

$sql = new sql();
$sql->query('CREATE TABLE `house2processing_center`(
    `company_id` TINYINT UNSIGNED NOT NULL,
    `house_id` SMALLINT UNSIGNED NOT NULL,
    `center_id` TINYINT UNSIGNED NOT NULL,
    `identifier` VARCHAR(16) NOT NULL,
    KEY `id`(`company_id`, `house_id`)
    )Engine=InnoDB DEFAULT CHARSET=utf8');
$sql->execute('Проблемы при создании таблицы связей дома и просессингового центра.');