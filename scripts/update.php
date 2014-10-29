<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/vendor/autoload.php");
use \boxxy\classes\di;
model_environment::before();

$em = di::get('em');
$dbal = $em->getConnection();

$dbal->exec("CREATE TABLE IF NOT EXISTS workgroup2work (
  workgroup_id SMALLINT(5) UNSIGNED NOT NULL,
  work_id SMALLINT(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$stmt = $dbal->query('SELECT id, workgroup_id FROM works');
while($row = $stmt->fetch()){
  $dbal->exec("INSERT INTO workgroup2work SET workgroup_id = ". $row['workgroup_id'].", work_id = ".$row['id']);
}

$dbal->exec("ALTER TABLE works DROP workgroup_id");