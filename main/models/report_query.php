<?php namespace main\models;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use domain\query;

class report_query{

  private $em;
  private $session;
  private $params = [
                     'departments' => [],
                     'time_begin' => 0,
                     'time_end' => 0,
                     'status' => [],
                     'work_types' => [],
                     'query_types' => [],
                     'streets' => [],
                     'houses' => []
                    ];

  public function __construct(EntityManager $em, Session $session){
    $this->em = $em;
    $this->session = $session;
    if(empty($this->session->get('report_query')))
      $this->init_default_params();
    else
      $this->params = $this->session->get('report_query');
  }

  public function init_default_params(){
    $params['time_begin'] = strtotime('midnight');
    $params['time_end'] = strtotime('tomorrow');
    $params['status'] = query::$status_list;
    $params['work_types'] = [];
    $params['query_types'] = [];
    $params['streets'] = [];
    $params['houses'] = [];
    $params['departments'] = [];
    $this->save_params($params);
  }

  public function save_params(array $params){
    foreach($params as $param => $value)
      if(array_key_exists($param, $this->params))
        $this->params[$param] = $value;
    $this->session->set('report_query', $this->params);
  }

  public function set_time_begin($time){
    $this->save_params(['time_begin' => $time]);
  }

  public function set_time_end($time){
    $this->save_params(['time_end' => $time]);
  }

  public function set_status($status){
    $check = in_array($status, query::$status_list, true);
    $status = ($check)? [$status]: query::$status_list;
    $this->save_params(['status' => $status]);
  }

  public function set_worktype($workgroup_id){
    $workgroup = $this->em->find('domain\workgroup', $workgroup_id);
    $type =  ($workgroup)? [$workgroup->get_id()]: [];
    $this->save_params(['work_types' => $type]);
  }

  public function set_department($department_id){
    $department = $this->em->find('domain\department', $department_id);
    $params['departments'] = ($department)? [$department->get_id()]: [];
    $params['streets'] = [];
    $params['houses'] = [];
    $this->save_params($params);
  }

  public function set_query_type($query_type_id){
    $query_type = $this->em->find('domain\query_type', $query_type_id);
    $type =  ($query_type)? [$query_type->get_id()]: [];
    $this->save_params(['query_types' => $type]);
  }

  public function set_street($street_id){
    $streets = $this->em->getRepository('domain\street')
                        ->findAll();
    $street_id_list = [];
    foreach($streets as $street)
      $street_id_list[] = $street->get_id();
    if(!in_array((int) $street_id, $street_id_list, true)){
      $params['departments'] = [];
      $params['streets'] = [];
      $params['houses'] = [];
    }else{
      $houses = $this->em->getRepository('domain\house')
                         ->findByStreet($street_id);
      $house_id_list = [];
      foreach($houses as $house)
        $house_id_list[] = $house->get_id();
      $params['departments'] = [];
      $params['streets'] = [$street_id];
      $params['houses'] = $house_id_list;
    }
    $this->save_params($params);
  }

  public function set_house($house_id){
    $house = $this->em->find('domain\house', $house_id);
    if($house)
      $this->save_params(['houses' => [$house->get_id()]]);
    else
      $this->set_street($this->params['streets'][0]);
  }

  public function get_queries(){
    return $this->em->getRepository('domain\query')
                    ->findByParams($this->params);
  }

  public function get_filters(){
    $filters['time_open_begin'] = $this->params['time_begin'];
    $filters['time_open_end'] = $this->params['time_end'];
    $filters['department'] = $this->params['departments'];
    if(count($this->params['status']) === 1)
      $filters['status'] = $this->params['status'][0];
    else
      $filters['status'] = null;
    if(count($this->params['work_types']) === 1)
      $filters['work_type'] = $this->params['work_types'][0];
    else
      $filters['work_type'] = null;
    if(count($this->params['query_types']) === 1)
      $filters['query_types'] = $this->params['query_types'][0];
    else
      $filters['query_types'] = null;
    if(count($this->params['streets']) === 1)
      $filters['street'] = $this->params['streets'][0];
    else
      $filters['street'] = null;
    if(count($this->params['houses']) === 1)
      $filters['house'] = $this->params['houses'][0];
    else
      $filters['house'] = null;
    return $filters;
  }
}