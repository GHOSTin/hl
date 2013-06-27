<?php
# подключаем фреймворк
define('ROOT' , __DIR__);
require_once(ROOT."/framework/framework.php");

try{
    model_environment::create_batabase_connection();

        
}catch(exception $e){
    die('Обновление завершилось неудачно.');
}