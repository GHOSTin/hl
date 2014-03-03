<?php
define('ROOT' ,__DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$stmt = $pdo->query('SELECT `id`, `department_id` FROM `houses`');
$houses = [];
while($row = $stmt->fetch()){
  $houses[$row['id']] = $row['department_id'];
}

$stmt = $pdo->query('SELECT `id`, `house_id` FROM `queries` WHERE
  `opentime` > '.mktime(0, 0, 0, 1, 1, 2014 ));
$count = $stmt->rowCount();
$i = 0;
while($row = $stmt->fetch()){
  update($pdo, $row['id'], $houses[$row['house_id']]);
  print $i.PHP_EOL;
  $i++;
}

print $i.PHP_EOL;


function update($pdo, $query_id, $department){
  $pdo->exec('UPDATE `queries` SET `department_id` = '.$department.' WHERE
    `id` = '.$query_id);
}
