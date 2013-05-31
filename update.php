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
    $sql->query("ALTER TABLE `meters` add COLUMN `service` SET('hot_water', 'cold_water', 'electrical') NOT NULL");
    $sql->execute('Столбец service не был создан.');

    $sql = new sql();
    $sql->query('ALTER TABLE `meters` add COLUMN `rates` TINYINT UNSIGNED NOT NULL DEFAULT 1');
    $sql->execute('Столбец rates не был создан.');

    $sql = new sql();
    $sql->query("ALTER TABLE `meters` add COLUMN `periods` VARCHAR(255) NOT NULL");
    $sql->execute('Столбец periods не был создан.');

    $sql = new sql();
    $sql->query("ALTER TABLE `number2meter` CHANGE COLUMN `service_id` `service` ENUM('hot_water', 'cold_water', 'electrical') NOT NULL");
    $sql->execute('Столбец rates не был создан.');

    $sql = new sql();
    $sql->query('DROP TABLE `accrualgroups`, `accrualgroups__log`, `accruals`, 
        `accruals__log`, `numbersessions`, `receipts`, `receipts__log`, `services`');
    $sql->execute('Таблицы не были удалены.');

}catch(exception $e){
    die($e->getMessage());
}