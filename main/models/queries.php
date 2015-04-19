<?php namespace main\models;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use domain\query;
use domain\user;

class queries{

  private $em;
  private $session;
  private $user;
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

  public function __construct(EntityManager $em, Session $session, user $user){
    $this->em = $em;
    $this->user = $user;
    $this->session = $session;
    if(empty($this->session->get('query'))){
      $this->init_default_params();
    }else
      $this->params = $this->session->get('query');
  }

  public function get_params(){
    return $this->params;
  }

  public function get_queries(){
    return $this->em->getRepository('domain\query')
                    ->findByParams($this->params);
  }

  public function get_departments(){
    $departments = $this->user->get_restriction('departments');
    if(!empty($departments))
      return $this->em->getRepository('domain\department')
                      ->findByid($departments, ['name' => 'ASC']);
    else
      return $this->em->getRepository('domain\department')
                      ->findAll(['name' => 'ASC']);
  }

  public function get_categories(){
    $categories = $this->user->get_restriction('categories');
    if(!empty($categories))
      return $this->em->getRepository('domain\workgroup')
                      ->findById($categories, ['name' => 'ASC']);
    else
      return $this->em->getRepository('domain\workgroup')
                      ->findAll(['name' => 'ASC']);
  }

  public function get_houses_by_street($street_id){
    if(!empty($this->user->get_restriction('departments'))){
      $houses = $this->em->getRepository('domain\house')
                         ->findBy([
                                   'department' => $this->user->get_restriction('departments'),
                                   'street' => $street_id
                                  ]);
    }else{
      $houses = $this->em->getRepository('domain\house')
                         ->findByStreet($street_id);
    }
    natsort($houses);
    return $houses;
  }

  public function get_streets(){
    if(!empty($this->user->get_restriction('departments'))){
      $streets = [];
      $houses = $this->em->getRepository('domain\house')
                         ->findBy(['department' => $this->user->get_restriction('departments')]);
      if(!empty($houses))
        foreach($houses as $house){
          $street = $house->get_street();
          if(!isset($streets[$street->get_id()]))
            $streets[$street->get_id()] = $street;
        }
      natsort($streets);
      return array_values($streets);
    }else
      return $this->em->getRepository('domain\street')
                      ->findAll(['name' => 'ASC']);
  }

  public function init_default_params(){
    $params['departments'] = $this->user->get_restriction('departments');
    $params['work_types'] = $this->user->get_restriction('categories');
    $params['time_begin'] = strtotime('midnight');
    $params['time_end'] = strtotime('tomorrow');
    $params['status'] = query::$status_list;
    $params['query_types'] = [];
    $params['streets'] = [];
    $params['houses'] = [];
    $this->save_params($params);
  }

  public function get_filter_values(){
    if(count($this->params['status']) === 1)
      $params['status'] = $this->params['status'][0];
    else
      $params['status'] = null;
    if(count($this->params['departments']) === 1)
      $params['department'] = $this->params['departments'][0];
    else
      $params['department'] = null;
    if(count($this->params['work_types']) === 1)
      $params['work_type'] = $this->params['work_types'][0];
    else
      $params['work_type'] = null;
    if(count($this->params['query_types']) === 1)
      $params['query_types'] = $this->params['query_types'][0];
    else
      $params['query_types'] = null;
    if(count($this->params['streets']) === 1)
      $params['streets'] = $this->params['streets'][0];
    else
      $params['streets'] = null;
    if(count($this->params['houses']) === 1)
      $params['house'] = $this->params['houses'][0];
    else
      $params['house'] = null;
    return $params;
  }

  public function get_timeline(){
    return strtotime('noon', $this->params['time_begin']);
  }

  public function save_params(array $params){
    foreach($params as $param => $value)
      if(array_key_exists($param, $this->params))
        $this->params[$param] = $value;
    $this->session->set('query', $this->params);
  }

  public function set_status($status){
    $check = in_array($status, query::$status_list, true);
    $status = ($check)? [$status]: query::$status_list;
    $this->save_params(['status' => $status]);
  }

  public function set_department($department_id){
    $department = $this->em->find('domain\department', $department_id);
    $departments = $this->user->get_restriction('departments');
    $check1 = empty($departments) && !is_null($department);
    $check2 = !empty($departments) && in_array($department_id, $departments);
    if($check1 || $check2)
      $departments = [$department_id];
    $params['departments'] = $departments;
    $params['streets'] = [];
    $params['houses'] = [];
    $this->save_params($params);
  }

  public function set_street($street_id){
    $street_id_list = [];
    foreach($this->get_streets() as $street)
      $street_id_list[] = $street->get_id();
    if(!in_array((int) $street_id, $street_id_list, true)){
      $params['departments'] = [];
      $params['streets'] = [];
      $params['houses'] = [];
    }else{
      $house_id_list = [];
      foreach($this->get_houses_by_street($street_id) as $house)
        $house_id_list[] = $house->get_id();
      $params['departments'] = [];
      $params['streets'] = [$street_id];
      $params['houses'] = $house_id_list;
    }
    $this->save_params($params);
  }

  public function set_time($time){
    $this->save_params([
                        'time_begin' => strtotime('midnight', $time),
                        'time_end' => strtotime('tomorrow', $time)
                       ]);
  }

  public function set_house($house_id){
    $house = $this->em->find('domain\house', $house_id);
    if($house)
      $this->save_params(['houses' => [$house->get_id()]]);
    else
      $this->set_street($this->params['streets'][0]);
  }

  public function set_query_type($query_type_id){
    $query_type = $this->em->find('domain\query_type', $query_type_id);
    $type = ($query_type)? [$query_type->get_id()]: [];
    $this->save_params(['query_types' => $type]);
  }

  public function set_work_type($category_id){
    $category = $this->em->find('domain\workgroup', $category_id);
    $categories = $this->user->get_restriction('categories');
    $check1 = empty($categories) && !is_null($category);
    $check2 = !empty($categories) && in_array($category_id, $categories);
    if($check1 || $check2)
      $categories = [$category_id];
    $this->save_params(['work_types' => $categories]);
  }
}