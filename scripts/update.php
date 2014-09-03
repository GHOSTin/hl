<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
class di{

  private static $instance;


  public static function get_instance(){
    return self::$instance;
  }

  public static function set_instance($instance){
    return self::$instance = $instance;
  }

  public static function get($key){
    $pimple = self::get_instance();
    return $pimple[$key];
  }
}

function framework_autoload($class_name){
  if(
    (0 === strpos($class_name, 'model_'))
    OR (0 === strpos($class_name, 'controller_'))
    OR (0 === strpos($class_name, 'data_'))
    OR (0 === strpos($class_name, 'repository_'))
  ){
    list($folder, $component) = explode('_', $class_name, 2);
    $file_path = ROOT.'/application/'.$component.'/'.$folder.'.php';
    if(file_exists($file_path))
      require_once $file_path;
  }else
    return;
}
spl_autoload_register('framework_autoload');
require_once(ROOT."/vendor/autoload.php");
model_environment::before();
$pdo = di::get('pdo');
$pdo->beginTransaction();
drop_table($pdo);
drop_company_id($pdo);
alter($pdo);
update_users($pdo);
metrics($pdo);
$pdo->commit();

function drop_table(PDO $pdo){
  $pdo->exec("DROP table companies, sessions, phrases, sms, sms2number, sms2query, sms2user, smsgroup2number, smsgroups, processing_centers, processing_center2number, house2processing_center, materials, materialgroups, meters, meter2data, number2meter, query_close_reasons, query2material");
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

  $pdo->exec("ALTER TABLE `task2user` ADD `id` INT NOT NULL AUTO_INCREMENT  FIRST,  ADD   PRIMARY KEY  (`id`)");
  $pdo->exec("ALTER TABLE `task2comment` ADD `id` INT NOT NULL AUTO_INCREMENT  FIRST,  ADD   PRIMARY KEY  (`id`)");
}

function update_users(PDO $pdo){
  $pdo->exec("UPDATE users SET firstname = 'админ', lastname = 'админ',
    midlename = 'админ' WHERE id = 1");
}

function metrics(PDO $pdo){
  $pdo->exec("CREATE TABLE IF NOT EXISTS `metrics` (
    `id` varchar(128) NOT NULL,
    `address` varchar(255) NOT NULL,
    `metrics` varchar(255) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
}