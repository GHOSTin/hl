<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use config\general as conf;

$DS = DIRECTORY_SEPARATOR;
$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen($DS.'scripts'))).$DS;
require_once($root."vendor/autoload.php");

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

$queries = [
  'ALTER TABLE `streets` CHANGE `id` `id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT',
  'ALTER TABLE `houses` CHANGE `id` `id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT',
  'ALTER TABLE `flats` CHANGE `id` `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT',
  'ALTER TABLE `numbers` CHANGE `id` `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT'
];

foreach($queries as $query)
  $pdo->exec($query);