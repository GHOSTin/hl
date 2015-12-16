<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use config\general as conf;
use domain\query;
use domain\number;

require_once(__DIR__."/vendor/autoload.php");

$dbParams = array(
  'driver'   => 'pdo_mysql',
  'host'     => conf::db_host,
  'user'     => conf::db_user,
  'password' => conf::db_password,
  'dbname'   => conf::db_name,
  'charset'  => 'utf8'
);
$config = Setup::createAnnotationMetadataConfiguration([__DIR__], true);
$em = EntityManager::create($dbParams, $config);
$pdo = $em->getConnection();

$string = file_get_contents('number2event.json');
$array = json_decode($string);
$stm = $pdo->prepare('INSERT INTO number2event set id = :idn,  number_id = :number_id, event_id = :event_id, time = :time, description = :description');
foreach($array as $a){
  $id = $a->number_id.'-'.$a->event_id.'-'.$a->time;
  $d = '';
  $stm->bindParam(':idn', $id);
  $stm->bindParam(':number_id', $a->number_id);
  $stm->bindParam(':event_id', $a->event_id);
  $stm->bindParam(':time', $a->time);
  $stm->bindParam(':description', $d);
  $stm->execute();
}