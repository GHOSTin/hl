<?php
// подключаем фреймворк
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");

try{
    model_environment::create_batabase_connection();
    $sql = new sql();
    $sql->query("ALTER TABLE `meter2data` ADD COLUMN `way` ENUM('answerphone', 'fax', 'personally', 'telephone') NOT NULL AFTER `value`");
    $sql->execute('Столбец way не был создан.');

    $sql = new sql();
    $sql->query("ALTER TABLE `meter2data` ADD COLUMN `timestamp` INT UNSIGNED NOT NULL AFTER `time`");
    $sql->execute('Столбец timestamp не был создан.');

    $sql = new sql();
    $sql->query("CREATE TABLE `processing_centers`(
            `id` TINYINT UNSIGNED NOT NULL,
            `name` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    $sql->execute('Таблица processing_centers не была создана.');

    $sql = new sql();
    $sql->query("CREATE TABLE `processing_center2number`(
                    `processing_center_id` TINYINT UNSIGNED NOT NULL,
                    `company_id` TINYINT UNSIGNED NOT NULL,
                    `number_id` MEDIUMINT UNSIGNED NOT NULL,
                    `identifier` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    $sql->execute('Таблица processing_center2number не была создана.');

}catch(exception $e){
    die($e->getMessage());
    die('Обновление завершилось неудачно.');
}