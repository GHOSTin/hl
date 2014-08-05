<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/scripts'))));
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);
model_environment::before();
$pdo = di::get('pdo');
$pdo->beginTransaction();
$stmt = $pdo->prepare("SELECT id, number FROM numbers");
if(!$stmt->execute())
  die('Запрос не выполнился'.PHP_EOL);

while($row = $stmt->fetch()){
  if(preg_match('/^37[0-9]{6}$/', $row['number'])){
    var_dump($row['number']);
    $st = $pdo->prepare('UPDATE numbers SET number = :number WHERE id = :id');
    $st->bindValue(':number', '00'.$row['number'], PDO::PARAM_STR);
    $st->bindValue(':id', $row['id'], PDO::PARAM_INT);
    if(!$st->execute())
      die('ПРоблема при обновлений лицевого счета'.PHP_EOL);
  }
}
$pdo->commit();