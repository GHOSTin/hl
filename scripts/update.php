<?php
use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use main\conf;

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
$queries = $em->getRepository('\domain\query')->findByInitiator('house');
foreach($queries as $query){
  $query->get_numbers()->clear();
}
$em->flush();