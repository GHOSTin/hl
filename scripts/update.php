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
$houses = $em->getRepository('domain\house')->findBy([], []);
foreach($houses as $house){
  print($house->get_street()->get_name().', №'.$house->get_number().PHP_EOL);
  foreach($house->get_numbers() as $number){
    $queries = $number->get_queries();
    foreach($queries as $query){
      if($query->get_initiator() == 'number'){
        if(empty($number->get_telephone()))
         update_telephone($number, $query->get_contact_telephone());
       if(empty($number->get_cellphone()))
         update_cellphone($number, $query->get_contact_cellphone());
      }
    }
  }
  $em->flush();
}

function update_telephone(number $number, $telephone){
  preg_match_all('/[0-9]/', $telephone, $tellphone_matches);
  $telephone = implode('', $tellphone_matches[0]);
  if(strlen($telephone) === 6){
    $number->set_telephone($telephone);
    print $number->get_number()." update telephone ".$telephone.PHP_EOL;
  }
}

function update_cellphone(number $number, $cellphone){
  preg_match_all('/[0-9]/', $cellphone, $matches);
  $cellphone = implode('', $matches[0]);
  if(preg_match('|^[78]|', $cellphone))
    $cellphone = substr($cellphone, 1, 10);
  if(strlen($cellphone) === 10){
    $number->set_cellphone($cellphone);
    print $number->get_number()." update cellphone ".$cellphone.PHP_EOL;
  }
}