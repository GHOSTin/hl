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
}catch(exception $e){
    die($e->getMessage());
}