<?php
define('ROOT' ,__DIR__);
require_once(ROOT."/framework/framework.php");
date_default_timezone_set(application_configuration::php_timezone);

$pdo = di::get('pdo');
$stmt = $pdo->exec('DROP TABLE IF EXISTS `cities__log`, `companies__log`, `flats__log`,
  `groups__log`, `houses__log`, `materialgroups__log`, `materials__log`,
  `numbers__log`, `queries__log`, `sessions__log`, `smsgroups__log`, `sms__log`,
  `streets__log`, `users__log`, `workgroups__log`, `works__log`');

$flats = "SELECT `flatnumber` FROM `flats`";
$stmt = $pdo->query($flats);
while($row = $stmt->fetch()){
  if(!preg_match('|^[0-9]{1,3}.{0,1}[0-9]{0,1}$|', $row['flatnumber']))
    print $row['flatnumber'].PHP_EOL;
}

$houses = "SELECT `housenumber` FROM `houses`";
$stmt = $pdo->query($houses);
while($row = $stmt->fetch()){
  if(!preg_match('|^[0-9]{1,3}[/]{0,1}[А-Яа-я0-9]{0,2}$|u', $row['housenumber']))
    print $row['housenumber'].PHP_EOL;
}

$queries = "SELECT `id`, `description-open` FROM `queries`";
$stmt = $pdo->query($queries);
while($row = $stmt->fetch()){
  if(!preg_match('|^[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]{0,65535}$|u', $row['description-open'])){
    $s = $pdo->prepare('UPDATE `queries` SET `description-open` = :description
      WHERE `id` = :id');
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u', $row['description-open'], $matches);
    $description = implode('', $matches[0]);
    if(strlen($description) === 0)
      $description = 'Пустое описание';
    $s->bindValue(':description', $description, PDO::PARAM_STR);
    $s->bindValue(':id', (int) $row['id'], PDO::PARAM_INT);
    $s->execute();
  }
}

$queries = "SELECT `id`, `description-close` FROM `queries`";
$stmt = $pdo->query($queries);
while($row = $stmt->fetch()){
  if(!preg_match('|^[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]{0,65535}$|u', $row['description-close'])){
    $s = $pdo->prepare('UPDATE `queries` SET `description-close` = :description
      WHERE `id` = :id');
    preg_match_all('|[А-Яа-яёЁ0-9№"!?()/:;.,\*\-+= ]|u', $row['description-close'], $matches);
    $s->bindValue(':description', implode('', $matches[0]), PDO::PARAM_STR);
    $s->bindValue(':id', (int) $row['id'], PDO::PARAM_INT);
    $s->execute();
  }
}