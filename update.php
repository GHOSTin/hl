<?php
# подключаем фреймворк
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");

try{
    model_environment::create_batabase_connection();
    $sql = new sql();
    $sql->query('ALTER TABLE `flats` ADD INDEX `idx` (`id`)');
    $sql->execute('Индекc для идентификатора квартиры не был создан.');

    $sql = new sql();
    $sql->query('ALTER TABLE `meters` DROP COLUMN `status`');
    $sql->execute('Столбец status не был удален.');

    $sql = new sql();
    $sql->query('ALTER TABLE `meters` DROP COLUMN `service_id`');
    $sql->execute('Столбец service_id не был удален.');

    $sql = new sql();
    $sql->query('ALTER TABLE `meters` add COLUMN `capacity` TINYINT UNSIGNED NOT NULL DEFAULT 1');
    $sql->execute('Столбец capacity не был создан.');

    $sql = new sql();
    $sql->query('ALTER TABLE `meters` add COLUMN `rates` TINYINT UNSIGNED NOT NULL DEFAULT 1');
    $sql->execute('Столбец rates не был создан.');

    $sql = new sql();
    $sql->query('CREATE TABLE `meter2service`(
        `company_id` TINYINT(3) UNSIGNED NOT NULL,
        `meter_id` MEDIUMINT(8) UNSIGNED NOT NULL,
        `service_id` SMALLINT(5) UNSIGNED NOT NULL,
        UNIQUE KEY `id` (`company_id`,`meter_id`, `service_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
    $sql->execute('Таблица meter2service не была создана.');
}catch(exception $e){
    die($e->getMessage());
}