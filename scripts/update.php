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

$queries[] = 'CREATE TABLE IF NOT EXISTS files (
                path VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                time INT(10) UNSIGNED NOT NULL,
                user_id INT(10) UNSIGNED NOT NULL,
                UNIQUE KEY index_path (path)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

$queries[] = 'CREATE TABLE IF NOT EXISTS query2file (
                query_id BIGINT UNSIGNED NOT NULL,
                path VARCHAR(255) NOT NULL,
                UNIQUE KEY index_path (query_id, path)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

foreach($queries as $query)
  $pdo->exec($query);