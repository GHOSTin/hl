<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use config\general as conf;
use domain\query;
use domain\number;

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

$queries[] = 'CREATE TABLE outage(
                id INT UNSIGNED NOT NULL,
                begin INT UNSIGNED NOT NULL,
                target INT UNSIGNED NOT NULL,
                category_id INT UNSIGNED NOT NULL,
                user_id INT UNSIGNED NOT NULL,
                description VARCHAR(255) NOT NULL,
                PRIMARY KEY (id)
              )';

$queries[] = 'CREATE TABLE outage2house(
                outage_id INT UNSIGNED NOT NULL,
                house_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(outage_id, house_id)
              )';

$queries[] = 'CREATE TABLE outage2performer(
                outage_id INT UNSIGNED NOT NULL,
                user_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(outage_id, user_id)
              )';
foreach($queries as $query)
  $pdo->exec($query);