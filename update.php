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

}catch(exception $e){
    die('Обновление завершилось неудачно.');
}