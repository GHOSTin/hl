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
    $this->meter->verify('id');
  }

	public function verify(){
    if(func_num_args() < 0)
      throw new e_data('Параметры верификации не были переданы.');
    foreach(func_get_args() as $value)
      verify_number2meter::$value($this);
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
    $this->comment = (string) $comment;
  }

  public function set_date_checking($time){
    $this->date_checking = (int) $time;
  }

  public function set_date_install($time){
    $this->date_install = (int) $time;
  }

  public function set_date_release($time){
    $this->date_release = (int) $time;
  }

  public function set_period($period){
    $this->period = (int) $period;
  }

  public function set_place($place){
    $this->place = (string) $place;
  }

  public function set_serial($serial){
    $this->serial = (string) $serial;
  }
  
  public function set_service($service){
    $this->service = (string) $service;
  }

  public function set_status($status){
    $this->status = (string) $status;
  }
}