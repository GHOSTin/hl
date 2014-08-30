<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/framework/framework.php");
require_once(ROOT."/vendor/autoload.php");
model_environment::before();
$pdo = di::get('pdo');
$pdo->beginTransaction();
drop_table($pdo);
update_users($pdo);
$pdo->commit();

function drop_table(PDO $pdo){
  $pdo->exec("DROP table companies, sessions, phrases, sms, sms2number, sms2query, sms2user, smsgroup2number, smsgroups, processing_centers, processing_center2number, house2processing_center, materials, materialgroups, meters, meter2data, number2meter, query_close_reasons");
  // $pdo->exec("UPDATE companies SET name = 'Наш город' WHERE id = 2");
}

function update_users(PDO $pdo){
  $pdo->exec("UPDATE users SET firstname = 'админ', lastname = 'админ',
    midlename = 'админ' WHERE id = 1");
}