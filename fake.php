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

foreach(fake::get_user_instance(conf::authSalt) as $user){
  $user->unblock();
  $em->persist($user);
  $users[] = $user;
}

foreach(user::get_rules_list() as $rule){
  $users[0]->update_access($rule);
}

#departments
foreach(fake::get_department_instance() as $department){
  $em->persist($department);
  $departments[] = $department;
}

# streets
foreach(fake::get_street_instance() as $street){
  $em->persist($street);
  $streets[] = $street;
}

# houses
foreach($streets as $street){
  foreach(range(1, 100) as $i){
    $house = fake::get_house_instance($departments[array_rand($departments)], $street);
    $em->persist($house);
    $houses[] = $house;
  }
}

# flats
foreach($houses as $house){
  foreach(range(1, rand(4, 10)) as $i){
    $flat = fake::get_flat_instance($house, $i);
    $em->persist($flat);
    $flats[] = $flat;
  }
}

# numbers
foreach($flats as $flat){
  $number = fake::get_number_instance($flat);
  $em->persist($number);
}

# workgroups
foreach(fake::get_workgroup_instance() as $workgroup){
  $em->persist($workgroup);
  $workgroups[] = $workgroup;
}

# works
foreach(fake::get_work_instance() as $work){
  $em->persist($work);
  $works[] = $work;
}

# events
foreach(fake::get_event_instance() as $event){
  $em->persist($event);
  $events[] = $event;
}

# query_type
foreach(fake::get_query_type_instance() as $query_type){
  $em->persist($query_type);
  $query_types[] = $query_type;
}

foreach($workgroups as $workgroup){
  foreach(array_rand($works, rand(2,6)) as $i){
    $workgroup->add_work($works[$i]);
  }
  foreach(array_rand($events, rand(2,6)) as $i){
    $workgroup->add_event($events[$i]);
  }
}

$em->flush();