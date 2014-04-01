<?php
print 'Переразметка лицевых по участкам'.PHP_EOL;
if(!empty($argv[1]) & file_exists($argv[1])){
  define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
  require_once(ROOT."/framework/framework.php");
  date_default_timezone_set(application_configuration::php_timezone);
  $file = fopen($argv[1], 'r');
  model_environment::before();
  $pdo = di::get('pdo');
  $pdo->beginTransaction();
  $i = 0;
  while($row = fgetcsv($file, 0, ';')){
    $stmt = $pdo->prepare('UPDATE `numbers` SET `number` = '.trim($row[1]).' WHERE
    `number` = '.trim($row[0]));
    $stmt->execute();
    if($stmt->rowCount() > 0)
      $i++;
  }
  // $pdo->commit();
  print 'Переразмечено лицевых: '.$i.PHP_EOL;
}else
  print 'Не задан  файл соответствия'.PHP_EOL;