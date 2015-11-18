<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use config\general as conf;
use domain\user;
use domain\department;
use domain\street;
use domain\house;
use domain\flat;
use domain\number;

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

$salt = conf::authSalt;

# users
$user = user::create('Некрасов', 'Евгений', 'Валерьевич', 'NekrasovEV', user::generate_hash('Aa123456', $salt));
foreach(user::get_rules_list() as $rule ){
  $user->update_access($rule);
}
$user->unblock();
$em->persist($user);

$departments = [];
#departments
foreach(range(1, 10) as $i){
  $department = department::create('Участок №'.$i);
  $em->persist($department);
  $departments[] = $department;
}

# streets
$streets = [];
$streets_name = [
            'Ватутина',
            'Ленина',
            'Емлина',
            'Береговая',
            'Гагарина',
            'Герцена',
            'Комсомольская',
            'Космонавтов',
            'Советская',
            'Строителей'
            ];

foreach($streets_name as $name){
  $street = street::new_instance($name);
  $em->persist($street);
  $streets[] = $street;
}

# houses
$houses = [];
foreach($streets as $street){
  foreach(range(1, 100) as $i){
    $house = house::new_instance($departments[array_rand($departments)], $street, get_house_letter());
    $em->persist($house);
    $houses[] = $house;
  }
}

foreach($houses as $house){
  foreach(range(1, 2) as $i){
    $flat = flat::new_instance($house, $i);
    $em->persist($flat);
    $flats[] = $flat;
  }
}

foreach($flats as $flat){
  $number = number::new_instance($flat->get_house(), $flat, fake::get_number_number(), fake::get_fio());
  $em->persist($number);
}

$em->flush();



function get_house_letter(){
  $letters = ['', 'А', 'Б', 'В', 'Г', 'Д'];
  return rand(1, 100).$letters[array_rand($letters)];
}



class fake{

  private static $lastnames = [
    'Некрасов',
    'Хорошавцев',
    'Немытов'
  ];

  private static $firstnames = [
    'Евгений',
    'Алексей',
    'Вячеслав'
  ];

  private static $middlenames = [
    'Валерьевич',
    'Сергеевич',
    'Анатольевич'
  ];

  private static $num = 0;

  public static function get_fio(){
    $lastname = self::$lastnames[array_rand(self::$lastnames)];
    $firstname = self::$firstnames[array_rand(self::$firstnames)];
    $middlename = self::$middlenames[array_rand(self::$middlenames)];
    return implode(' ', [$lastname, $firstname, $middlename]);
  }

  public static function get_number_number(){

    return ++self::$num;
  }
}