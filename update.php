<?php
# подключаем фреймворк
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");

try{
    model_environment::create_batabase_connection();
    $sql = new sql();
    $sql->query("ALTER TABLE `number2meter` add COLUMN `status` ENUM('enabled', 'disabled') NOT NULL AFTER `meter_id`");
    $sql->execute('Столбец status не был создан.');
}catch(exception $e){
    die('Обновление завершилось неудачно.');
}