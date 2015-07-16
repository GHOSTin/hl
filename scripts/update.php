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

$queries[] = 'CREATE TABLE meterage(
                id VARCHAR(255) NOT NULL,
                number_id INT UNSIGNED NOT NULL,
                time INT UNSIGNED NOT NULL,
                service VARCHAR(255) NOT NULL,
                tarif INT UNSIGNED NOT NULL,
                params TEXT,
                PRIMARY KEY (id),
                KEY (number_id),
                KEY (time)
              )';

foreach($queries as $query)
  $pdo->exec($query);