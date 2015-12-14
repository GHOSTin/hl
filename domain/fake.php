<?php namespace domain;

use Doctrine\ORM\EntityManager;

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

  private static  $workgroups_names = [
                                        'Сантехника',
                                        'Электротехника',
                                        'Строительство',
                                        'Благоустройство',
                                        'ИТР',
                                        'Канализация',
                                        'Отопление',
                                        'Горячее водоснабжение',
                                        'Холодное водоснабжение'
                                      ];

  private static $works_names = [
                                    'Ремонт сливного бачка',
                                    'Замена вводного автомата',
                                    'Замена выключателя',
                                    'Замена датчика света',
                                    'Замена розетки',
                                    'Замена кабеля',
                                    'отревизировал',
                                    'Подтянули бачок',
                                    'Прочистили КНЗ в подвале'
                                  ];
  private static  $events_names = [
                                    'Отключение электроэнергии',
                                    'Вручение уведомления',
                                    'Отказ об вручении уведомления',
                                    'Подключение электроэнергии',
                                    'Судебное обращение',
                                    'Реструктуризация долга',
                                    'Почтой с уведомлением'
                                  ];


  private static  $query_types_names = [
                                        'Аварийная',
                                        'На участок',
                                        'Плановая',
                                        'Перерасчет'
                                      ];
  private static  $api_keys_names = [
                                      'Даниловское',
                                      'Домплюс',
                                      'Фрисби'
                                    ];

  private static $streets_names = [
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

  private static $phrases_names = [
                                    'Отсутствует электроэнергия в кв.',
                                    'Отсутствует освещение у подъезда №',
                                    'Отсутствует отопление в',
                                    'Течь в'
                                    ];

  private static $groups_names = [
                                    'Мастера',
                                    'Электрики',
                                    'Сантехники',
                                    'Строители',
                                    'Уборщики'
                                    ];

  private static $num = 0;




  private $departments = [];
  private $numbers = [];

  public function __construct(EntityManager $em, $salt){
    $this->salt = $salt;
    $this->em = $em;
    $this->generate_entities();
  }

  public function generate_entities(){
    $this->generate_users();
    $this->generate_works();
    $this->generate_events();
    $this->generate_departments();
    $this->generate_streets();
    $this->generate_houses();
    $this->generate_flats();
    $this->generate_numbers();
    $this->generate_workgroups();
    $this->generate_query_types();
    $this->generate_groups();
    $this->generate_metrics();
    $this->generate_outages();
    $this->generate_api_keys();
    foreach($this->get_entities() as $ent){
      $this->em->persist($ent);
    }
    $this->em->flush();
    $this->generate_number2event();
  }

  public function generate_departments(){
    foreach(self::get_department_instance() as $department){
      $this->departments[] = $department;
    }
  }

  public function generate_streets(){
    foreach(self::get_street_instance() as $street){
      $this->streets[] = $street;
    }
  }

  public function generate_users(){
    foreach(self::get_user_instance($this->salt) as $user){
      $user->unblock();
      $this->users[] = $user;
    }
    foreach(user::get_rules_list() as $rule){
      $this->users[0]->update_access($rule);
    }
  }

  public function generate_houses(){
    foreach($this->streets as $street){
      foreach(range(1, 100) as $i){
        $this->houses[] = self::get_house_instance($this->get_random_department(), $street);
      }
    }
  }

  public function generate_numbers(){
    foreach($this->flats as $flat){
      $number = self::get_number_instance($flat);
      $meterage = self::get_meterage_instance($number);
      $this->numbers[] = $number;
      $this->meterages[] = $meterage;
    }
  }

  public function generate_number2event(){
    print 'Генерация событий лицевого счета'.PHP_EOL;
    foreach($this->numbers as $number){
      foreach($this->get_random_events() as $event){
        $number->add_event($event, date('d.m.Y'), 'Привет');
      }
    }
    $this->em->flush();
  }

  public function generate_flats(){
    foreach($this->houses as $house){
      foreach(range(1, rand(4, 10)) as $i){
        $this->flats[] = self::get_flat_instance($house, $i);
      }
    }
  }

  public function generate_workgroups(){
    foreach(self::get_workgroup_instance() as $workgroup){
      foreach($this->get_random_works() as $work){
        $workgroup->add_work($work);
      }
      foreach($this->get_random_events() as $event){
        $workgroup->add_event($event);
      }
      foreach(fake::get_phrase_instance($workgroup) as $phrase){
        $this->phrases[] = $phrase;
      }
      $this->workgroups[] = $workgroup;
    }
  }

  public function generate_works(){
    foreach(self::get_work_instance() as $work){
      $this->works[] = $work;
    }
  }

  public function generate_events(){
    foreach(self::get_event_instance() as $event){
      $this->events[] = $event;
    }
  }

  public function generate_query_types(){
    foreach(self::get_query_type_instance() as $query_type){
      $this->query_types[] = $query_type;
    }
  }

  public function generate_groups(){
    foreach(self::get_group_instance() as $group){
      foreach($this->get_random_users() as $user){
        $group->add_user($user);
      }
      $this->groups[] = $group;
    }
  }

  public function generate_metrics(){
    foreach(self::get_metrics_instance() as $metr){
      $this->metrics[] = $metr;
    }
  }

  public function generate_api_keys(){
    foreach(self::get_api_keys_instance() as $key){
      $this->api_keys[] = $key;
    }
  }

  public function generate_outages(){
    foreach(range(0, 100) as $i){
      $this->outages[] = fake::get_outage_instance(strtotime('- '.rand(0, 30).' days'), strtotime('+ '.rand(0, 30).' days'),
                                                    $this->get_random_workgroup(),
                                                    $this->get_random_user(),
                                                    $this->get_random_houses(),
                                                    $this->get_random_users(),
                                                    'Привет');
    }
  }

  public function get_random_department(){
    return $this->departments[array_rand($this->departments)];
  }

  public function get_random_workgroup(){
    return $this->workgroups[array_rand($this->workgroups)];
  }

  public function get_random_user(){
    return $this->users[array_rand($this->users)];
  }

  public function get_random_houses(){
    $houses = [];
    foreach(array_rand($this->houses, rand(2, 10)) as $i){
      $houses[] = $this->houses[$i];
    }
    return $houses;
  }

  public function get_random_users(){
    $users = [];
    foreach(array_rand($this->users, rand(2, count($this->users))) as $i){
      $users[] = $this->users[$i];
    }
    return $users;
  }

  public function get_random_works(){
    $works = [];
    foreach(array_rand($this->works, rand(2, count($this->works))) as $i){
      $works[] = $this->works[$i];
    }
    return $works;
  }

  public function get_random_events(){
    $events = [];
    foreach(array_rand($this->events, rand(2, count($this->events))) as $i){
      $events[] = $this->events[$i];
    }
    return $events;
  }

  public function get_departments(){
    return $this->departments;
  }

  public function get_streets(){
    return $this->streets;
  }

  public function get_houses(){
    return $this->houses;
  }

  public function get_flats(){
    return $this->flats;
  }

  public function get_users(){
    return $this->users;
  }

  public function get_entities(){
    return array_merge(
                        $this->users,
                        $this->departments,
                        $this->streets,
                        $this->houses,
                        $this->flats,
                        $this->numbers,
                        $this->workgroups,
                        $this->works,
                        $this->events,
                        $this->query_types,
                        $this->groups,
                        $this->metrics,
                        $this->phrases,
                        $this->outages,
                        $this->api_keys,
                        $this->meterages
                      );
  }

  public static function get_random_firstname(){
    return self::$firstnames[array_rand(self::$firstnames)];
  }

  public static function get_random_lastname(){
    return self::$lastnames[array_rand(self::$lastnames)];
  }

  public static function get_random_middlename(){
    return self::$middlenames[array_rand(self::$middlenames)];
  }

  public static function get_fio(){
    $lastname = self::get_random_firstname();
    $firstname = self::get_random_lastname();
    $middlename = self::get_random_middlename();
    return implode(' ', [$lastname, $firstname, $middlename]);
  }

  public static function get_workgroup_instance(){
    foreach(self::$workgroups_names as $name)
      yield workgroup::new_instance($name);
  }

  public static function get_work_instance(){
    foreach(self::$works_names as $name)
      yield work::new_instance($name);
  }

  public static function get_event_instance(){
    foreach(self::$events_names as $name)
      yield event::new_instance($name);
  }

  public static function get_query_type_instance(){
    foreach(self::$query_types_names as $name)
      yield query_type::new_instance($name);
  }

  public static function get_api_keys_instance(){
    foreach(self::$api_keys_names as $name)
      yield api_key::new_instance($name);
  }

  public static function get_number_instance(flat $flat){
    return number::new_instance($flat->get_house(), $flat, self::get_number_number(), self::get_fio());
  }

  public static function get_meterage_instance(number $number){
    return meterage::new_instance($number, time(), 'Горячее водоснабжение', '2', '125', '250');
  }

  public static function get_flat_instance(house $house, $i){
    return flat::new_instance($house, $i);
  }

  public static function get_house_instance(department $department, street $street){
    return house::new_instance($department, $street, self::get_house_letter());
  }

  public static function get_street_instance(){
    foreach(self::$streets_names as $name)
      yield street::new_instance($name);
  }

  public static function get_department_instance(){
    foreach(range(1, 10) as $i)
      yield department::create('Участок №'.$i);
  }

  public static function get_phrase_instance(workgroup $workgroup){
    foreach(self::$phrases_names as $name)
      yield phrase::new_instance($workgroup, $name);
  }

  public static function get_user_instance($salt){
    foreach(range(1, 10) as $i)
      yield user::create(self::get_random_lastname(),
                                 self::get_random_firstname(),
                                 self::get_random_middlename(),
                                 'user'.$i,
                                 user::generate_hash('user'.$i, $salt)
                                );

  }

  public static function get_metrics_instance(){
    $time = time();
    foreach(range(1000, 2000) as $i)
      yield metrics::new_instance(sha1($time.$i),
                                 $time,
                                 'Ватутина 52 кв. 19',
                                 'Привет'
                                );

  }

  public static function get_group_instance(){
    foreach(self::$groups_names as $name)
      yield group::new_instance($name);

  }

  public static function get_house_letter(){
    $letters = ['', 'А', 'Б', 'В', 'Г', 'Д'];
    return rand(1, 100).$letters[array_rand($letters)];
  }

  public static function get_outage_instance($begin,
                                              $target,
                                              workgroup $workgroup,
                                              user $user,
                                              $houses,
                                              $performers,
                                              $description
                                            ){
    return outage::new_instance($begin, $target, $workgroup, $user, $houses, $performers, $description);
  }

  public static function get_number_number(){
    return ++self::$num;
  }
}