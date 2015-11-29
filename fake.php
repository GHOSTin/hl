<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use config\general as conf;
use domain\user;
use domain\fake;

require_once("vendor/autoload.php");

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
$fake = new fake(conf::authSalt);

$users = $fake->get_users();

foreach($users as $user){
  $user->unblock();
}

foreach(user::get_rules_list() as $rule){
  $users[0]->update_access($rule);
}

foreach($fake->get_entities() as $ent){
  $em->persist($ent);
}
$em->flush();