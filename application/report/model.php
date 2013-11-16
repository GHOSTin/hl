<?php
class model_report{

  private $profile;
  private $params = [];

  private static $profiles = ['query'];

  public function __construct($name){
    $this->profile = (string) $name;
    if(!(in_array($this->profile, self::$profiles)))
        throw new e_model('Нет такого профиля.');
    $mem = new mem(__CLASS__);
    $this->params = $mem->get_params();
    if(empty($this->params)){
      $this->init_params();
      $mem->save($this->params);
    }
    unset($_SESSION['model']);
  }

  public function get_params(){
    return $this->params;
  }

  private function init_params(){
    $time = getdate();
    $this->params['profile'] = $this->profile;
    $this->params['time_open_begin'] = mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']) - 86400*21;
    $this->params['time_open_end'] = mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']);
  }

  public static function clear_filter_query(){
    $this->init_params();
    return $this->params;
  }

  public static function set_filter($key, $value){
      $session = model_session::get_session();
      $filters = $session->get('filters');
      if(!is_array($filters))
          $filters = [];
      $filters[$key] = $value;
      $session->set('filters', $filters);
  }

  public function set_filter_query_department($department_id){
    if($department_id === 'all')
      $this->params['department'] = [];
    else{
      $department = (new model_department(model_session::get_company()))
        ->get_department($department_id);
      $this->params['department'][0] = $department->get_id();
    }
    (new mem(__CLASS__))->save($this->params);
  }

  public function set_filter_query_status($status){
    if($status === 'all')
      $this->params['status'] = null;
    else{
      $query = new data_query();
      $query->set_status($status);
      $query->verify('status');
      $this->params['status'] = $status;
    }
    (new mem(__CLASS__))->save($this->params);
  }

  public static function set_filter_query_street($street_id){
      $session = model_session::get_session();
      $filters = $session->get('filters');
      if($street_id === 'all'){
          unset($filters['street_id']);
          unset($filters['house_id']);
      }else{
          $query = new data_query();
          $query->street_id = $street_id;
          $query->verify('street_id');
          $filters['street_id'] = $street_id;
          unset($filters['house_id']);
      }
      $session->set('filters', $filters);
  }

  public static function set_filter_query_house($house_id){
      $session = model_session::get_session();
      $filters = $session->get('filters');
      if($house_id === 'all'){
          unset($filters['house_id']);
      }else{
          $query = new data_query();
          $query->house_id = $house_id;
          $query->verify('house_id');
          $filters['house_id'] = $house_id;
      }
      $session->set('filters', $filters);
  }

  public static function set_filter_query_worktype($worktype_id){
      $session = model_session::get_session();
      $filters = $session->get('filters');
      if($worktype_id === 'all')
          unset($filters['worktype_id']);
      else{
          $query = new data_query();
          $query->worktype_id = $worktype_id;
          $query->verify('work_type_id');
          $filters['worktype_id'] = $worktype_id;
      }
      $session->set('filters', $filters);
  }
}