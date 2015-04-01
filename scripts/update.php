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

$queries[] = 'ALTER TABLE queries CHANGE warning_type query_type_id INT(10) UNSIGNED NOT NULL';

$queries[] = 'CREATE TABLE IF NOT EXISTS query_types (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id),
                UNIQUE KEY index_name (name)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

$queries[] = "INSERT INTO query_types (name) VALUES
              ('Аварийная'),
              ('На участок'),
              ('Плановая')";

$queries[] = 'ALTER TABLE queries DROP COLUMN payment_status ';

foreach($queries as $query)
  $pdo->exec($query);