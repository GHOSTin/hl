<?php
function update($pdo, $query_id, $department){
  $pdo->exec('UPDATE `queries` SET `department_id` = '.$department.' WHERE
    `id` = '.$query_id);
}

if(!empty($argv[1])){
  print 'Переразметка заявок по участкам c '.$argv[1].PHP_EOL;
  define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
  require_once(ROOT."/framework/framework.php");
  date_default_timezone_set(application_configuration::php_timezone);
  model_environment::before();
  $pdo = di::get('pdo');
  $pdo->beginTransaction();
  $stmt = $pdo->query('SELECT `id`, `department_id` FROM `houses`');
  $houses = [];
  while($row = $stmt->fetch())
    $houses[$row['id']] = $row['department_id'];
  $stmt = $pdo->query('SELECT `id`, `house_id` FROM `queries` WHERE
    `opentime` > '.strtotime($argv[1]));
  $count = $stmt->rowCount();
  $i = 0;
  while($row = $stmt->fetch()){
    update($pdo, $row['id'], $houses[$row['house_id']]);
    $i++;
  }
  $pdo->commit();
  print 'Переразмечено заявок: '.$i.PHP_EOL;
}