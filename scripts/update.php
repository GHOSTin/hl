<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/framework/framework.php");
require_once(ROOT."/vendor/autoload.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->beginTransaction();
update_companies($pdo);
$pdo->commit();

function update_companies(PDO $pdo){
  $pdo->exec("UPDATE companies SET name = 'Тестовая' WHERE id = 1");
  $pdo->exec("UPDATE companies SET name = 'Наш город' WHERE id = 2");
}