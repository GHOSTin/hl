<?php
class data_number2meter{

	private $service;
	private $serial;
	private $date_release;
	private $date_install;
	private $date_checking;
  private $date_next_checking;
	private $period;
	private $place;
  private $comment;
  private $status;
  private $meter;
  private $number;
  private $values = [];

  public function __call($method, $args){
    return $this->meter->$method($args);
  }

  public function __construct(data_meter $meter){
    $this->meter = $meter;
  }

  public function add_value(data_meter2data $m2d){
    if(in_array($m2d->get_time(), $this->values, true))
      throw new e_model('В счетчике уже добавлено показание за данный месяц.');
    $this->values[$m2d->get_time()] = $m2d;
  }

  public function get_comment(){
    return $this->comment;
  }

  public function get_date_checking(){
    return $this->date_checking;
  }

  public function get_date_install(){
    return $this->date_install;
  }

  public function get_date_release(){
    return $this->date_release;
  }

  public function get_date_next_checking(){
    return $this->date_next_checking;
  }

  public function get_period(){
    return $this->period;
  }

  public function get_place(){
    return $this->place;
  }

  public function get_serial(){
    return $this->serial;
  }

  public function get_service(){
    return $this->service;
  }

  public function get_status(){
    return $this->status;
  }

  public function get_values(){
    return $this->values;
  }

  public function set_comment($comment){
    self::verify_comment($comment);
    $this->comment = $comment;
  }

  public function set_date_checking($time){
    $time = (int) $time;
    self::verify_date_checking($time);
    if($this->date_install > $time)
      throw new e_model('Время последней поверки счетчика не может быть
      больше времени установки счетчика.');
    $this->date_checking = $time;
  }

  public function set_date_install($time){
    $time = (int) $time;
    self::verify_date_install($time);
    if($this->date_release > $time)
      throw new e_model('Время установки счетчика не может быть больше времени
      произдовства счетчика.');
    if(!is_null($this->date_checking))
      if($this->date_checking < $time)
        throw new e_model('Время установки счетчика не может быть больше времени
        последней поверки счетчика.');
    $this->date_install = $time;
  }

  public function set_date_release($time){
    $time = (int) $time;
    self::verify_date_release($time);
    if(!is_null($this->date_install))
      if($this->date_install < $time)
        throw new e_model('Время производства счетчика не может быть больше
        времени установки счетчика');
    $this->date_release = $time;
  }

  public function set_period($period){
    $period = (int) $period;
    data_meter::verify_period($period);
    $this->period = $period;
  }

  public function set_place($place){
    self::verify_place($place);
    $this->place = $place;
  }

  public function set_serial($serial){
    self::verify_serial($serial);
    $this->serial = $serial;
  }
  
  public function set_service($service){
    self::verify_service($service);
    $this->service = $service;
  }

  public function set_status($status){
    self::verify_status($status);
    $this->status = $status;
  }

  public static function verify_date_checking($time){
    if($time < 1)
      throw new e_model('Время даты поверки задано не верно.');
  }

  public static function verify_date_install($time){
    if($time < 1)
      throw new e_model('Время даты установки задано не верно.');
  }

  public static function verify_comment($comment){
    if(!preg_match('/^[А-Яа-я0-9\., ]{0,255}$/u', $comment))
      throw new e_model('Комментарий задан не верно.');
  }

  public static function verify_date_release($time){
    if($time < 1)
      throw new e_model('Дата выпуска счетчика задана не верно.');
  }

  public static function verify_place($place){
    if(!in_array($place, ['bathroom', 'kitchen', 'toilet'], true))
      throw new e_model('Место установки задано не верно.');
  }

  public static function verify_serial($serial){
    if(!preg_match('/^[а-яА-Я0-9]{1,20}$/u', $serial))
      throw new e_model('Заводской номер счетчика задано не верно.');
  }

  public static function verify_service($service){
    if(!in_array($service, data_meter::get_service_list()))
      throw new e_model('Услуга задана не верно.');
  }

  public static function verify_status($status){
    if(!in_array($status, ['enabled', 'disabled'], true))
      throw new e_model('Статус счетчика задан не верно.');
  }
}