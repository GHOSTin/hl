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

$query = "ALTER TABLE numbers ADD notification_rules VARCHAR(255) NOT NULL DEFAULT '{}'";
$pdo->exec($query);

$query = "CREATE TABLE IF NOT EXISTS events(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$pdo->exec($query);

$query = "CREATE TABLE IF NOT EXISTS workgroup2event(
            workgroup_id INT UNSIGNED NOT NULL,
            event_id INT UNSIGNED NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$pdo->exec($query);

$query = "CREATE TABLE IF NOT EXISTS number2event(
            time INT UNSIGNED NOT NULL,
            number_id INT UNSIGNED NOT NULL,
            event_id INT UNSIGNED NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$pdo->exec($query);
