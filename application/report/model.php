<?php
class model_report{

  private $profile;
  private $params = [];

  private static $profiles = ['query'];

  public function __construct($name){
    $this->profile = (string) $name;
    if(!(in_array($this->profile, self::$profiles)))
        throw new RuntimeException('Нет такого профиля.');
    $mem = new mem(__CLASS__);
    $this->params = $mem->get_params();
    if(empty($this->params)){
      $this->init_params();
      $mem->save($this->params);
    }
    // unset($_SESSION['model']);
  }

  public function get_params(){
    return $this->params;
  }

  private function init_params(){
    $time = getdate();
    $this->params['profile'] = $this->profile;
    $this->params['time_open_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']);
    $this->params['time_open_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
    $this->params['department'] = [];
    $this->params['work_type'] = null;
    $this->params['street'] = null;
    $this->params['house'] = null;
  }

  public function clear_filter_query(){
    $this->init_params();
    (new mem(__CLASS__))->save($this->params);
  }

  public function set_time_begin($time){
    $this->params['time_open_begin'] = $time;
    (new mem(__CLASS__))->save($this->params);
  }

  public function set_time_end($time){
    $this->params['time_open_end'] = $time;
    (new mem(__CLASS__))->save($this->params);
  }

  public function set_filter_query_department($department_id){
    if($department_id === 'all')
      $this->params['department'] = [];
    else{
      $department = (new model_department(di::get('company')))
        ->get_department($department_id);
      $this->params['department'][0] = $department->get_id();
    }
    $this->params['street'] = null;
    $this->params['house'] = null;
    (new mem(__CLASS__))->save($this->params);
  }

  public function set_filter_query_status($status){
    if($status === 'all')
      $this->params['status'] = null;
    else{
      $this->params['status'] = $status;
    }
    (new mem(__CLASS__))->save($this->params);
  }

  public function set_filter_query_street($street_id){
    if($street_id === 'all'){
      $this->params['street'] = null;
      $this->params['house'] = null;
    }else{
      $street = di::get('em')->find('data_street', $street_id);
      $this->params['street'] = $street->get_id();
      $this->params['house'] = null;
    }
    $this->params['department'] = [];
    (new mem(__CLASS__))->save($this->params);
    return $street;
  }

  public function set_filter_query_house($house_id){
    if($house_id === 'all'){
      $this->params['house'] = null;
    }else{
      $house = di::get('em')->find('data_house', $house_id);
      $this->params['house'] = $house->get_id();
    }
    (new mem(__CLASS__))->save($this->params);
  }

  public function set_filter_query_worktype($worktype_id){
    if($worktype_id === 'all')
      $this->params['work_type'] = null;
    else{
      $work_type = (new model_query_work_type(di::get('company')))
        ->get_query_work_type($worktype_id);
      $this->params['work_type'] = $work_type->get_id();
    }
    (new mem(__CLASS__))->save($this->params);
  }
}