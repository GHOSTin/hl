<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/framework/framework.php");
require_once(ROOT."/vendor/autoload.php");
model_environment::before();
$pdo = di::get('pdo');
$pdo->beginTransaction();
drop_table($pdo);
drop_company_id($pdo);
alter($pdo);
update_users($pdo);
$pdo->commit();

function drop_table(PDO $pdo){
  $pdo->exec("DROP table companies, sessions, phrases, sms, sms2number, sms2query, sms2user, smsgroup2number, smsgroups, processing_centers, processing_center2number, house2processing_center, materials, materialgroups, meters, meter2data, number2meter, query_close_reasons, query2material");
  $pdo->exec("UPDATE companies SET name = 'Наш город' WHERE id = 2");
}

function drop_company_id(PDO $pdo){
  $pdo->exec("ALTER TABLE accruals DROP company_id");
  $pdo->exec("ALTER TABLE cities DROP company_id");
  $pdo->exec("ALTER TABLE client_queries DROP company_id");
  $pdo->exec("ALTER TABLE departments DROP company_id");
  $pdo->exec("ALTER TABLE flats DROP company_id");
  $pdo->exec("ALTER TABLE groups DROP company_id");
  $pdo->exec("ALTER TABLE houses DROP company_id");
  $pdo->exec("ALTER TABLE numbers DROP company_id");
  $pdo->exec("ALTER TABLE profiles DROP company_id");
  $pdo->exec("ALTER TABLE queries DROP company_id");
  $pdo->exec("ALTER TABLE query2comment DROP company_id");
  $pdo->exec("ALTER TABLE query2number DROP company_id");
  $pdo->exec("ALTER TABLE query2user DROP company_id");
  $pdo->exec("ALTER TABLE query2work DROP company_id");
  $pdo->exec("ALTER TABLE query_worktypes DROP company_id");
  $pdo->exec("ALTER TABLE streets DROP company_id");
  $pdo->exec("ALTER TABLE users DROP company_id");
  $pdo->exec("ALTER TABLE workgroups DROP company_id");
  $pdo->exec("ALTER TABLE works DROP company_id");
}

function alter(PDO $pdo){
  $pdo->exec("ALTER TABLE queries CHANGE `initiator-type` initiator enum('number','house') NOT NULL");
  $pdo->exec("ALTER TABLE queries CHANGE `payment-status` payment_status enum('paid','unpaid','recalculation') NOT NULL DEFAULT 'unpaid'");
  $pdo->exec("ALTER TABLE queries CHANGE `warning-type` warning_type enum('hight','normal','planned') NOT NULL DEFAULT 'normal'");
  $pdo->exec("ALTER TABLE queries CHANGE `description-open` description text");
  $pdo->exec("ALTER TABLE queries CHANGE `description-close` reason text");
  $pdo->exec("ALTER TABLE queries CHANGE `addinfo-name` contact_fio varchar(255) DEFAULT NULL");
  $pdo->exec("ALTER TABLE queries CHANGE  `addinfo-telephone` contact_telephone varchar(11) DEFAULT NULL");
  $pdo->exec("ALTER TABLE queries CHANGE `addinfo-cellphone` contact_cellphone varchar(11) DEFAULT NULL");
}

function update_users(PDO $pdo){
  $pdo->exec("UPDATE users SET firstname = 'админ', lastname = 'админ',
    midlename = 'админ' WHERE id = 1");
}
