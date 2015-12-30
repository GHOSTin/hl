<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use config\general as conf;
use domain\user;
use domain\department;
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
// $user = user::create('Некрасов',
//                      'Евгений',
//                      'Валерьевич',
//                      'NekrasovEV',
//                      user::generate_hash('Aa123456', conf::authSalt)
//                     );
// $user->unblock();
// foreach(user::get_rules_list() as $rule){
//   $user->update_access($rule);
// }
// $em->persist($user);

$department = department::create('Участок №1');
$em->persist($department);
$em->flush();
