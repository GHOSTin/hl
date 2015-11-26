<?php namespace domain;

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

  private static $num = 0;

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

  public static function get_number_instance(flat $flat){
    return number::new_instance($flat->get_house(), $flat, self::get_number_number(), self::get_fio());
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

  public static function get_user_instance($salt){
    foreach(range(1, 10) as $i)
      yield $user = user::create(self::get_random_lastname(),
                                 self::get_random_firstname(),
                                 self::get_random_middlename(),
                                 'admin'.$i,
                                 user::generate_hash('admin'.$i, $salt)
                                );

  }

  public static function get_house_letter(){
    $letters = ['', 'А', 'Б', 'В', 'Г', 'Д'];
    return rand(1, 100).$letters[array_rand($letters)];
  }

  public static function get_number_number(){
    return ++self::$num;
  }
}