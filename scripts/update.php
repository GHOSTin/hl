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

$dbal->exec("ALTER TABLE works DROP workgroup_id");
$dbal->exec("DROP INDEX company_id ON workgroups");
$dbal->exec("ALTER TABLE workgroups ADD PRIMARY KEY id (id)");
$dbal->exec("ALTER TABLE workgroups CHANGE id id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT");
$dbal->exec("DROP INDEX workgroup_id ON works");
$dbal->exec("DROP INDEX id ON works");
$dbal->exec("ALTER TABLE works ADD PRIMARY KEY id (id)");
$dbal->exec("ALTER TABLE `works` CHANGE `id` `id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT;");
$dbal->exec("UPDATE workgroups SET name = 'Не выбран' WHERE id = 1");
$dbal->exec("UPDATE workgroups SET name = 'Электрика' WHERE id = 3");
$dbal->exec("UPDATE workgroups SET name = 'Строительство' WHERE id = 4");
$dbal->exec("UPDATE workgroups SET name = 'Благоустройство' WHERE id = 5");
$dbal->exec("UPDATE workgroups SET name = 'ИТР' WHERE id = 6");
$dbal->exec("UPDATE workgroup2work SET workgroup_id = 3 WHERE workgroup_id = 1");
$dbal->exec("DROP TABLE query_worktypes");
$dbal->exec("UPDATE workgroup2work SET workgroup_id = 1 WHERE workgroup_id > 6");
$dbal->exec("DELETE FROM workgroups WHERE id > 6");