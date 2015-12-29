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

$numbers = $em->getRepository('domain\number')->findById([30540, 30541, 30542]);
$i = 2000000;
foreach($numbers as $number){
  $number->set_number($i);
  $i = $i + 1;
}
$em->find('domain\number', 34398)->set_number(2000005);
$em->flush();
