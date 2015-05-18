<?php namespace main\models;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use Silex\Application;
use domain\user;
use domain\query;

class report_queries extends model{

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

  public function __construct(Application $app, Twig_Environment $twig, EntityManager $em, user $user, Session $session){
    if(!$user->check_access('reports/general_access'))
      throw new RuntimeException();
    parent::__construct($app, $twig, $em, $user);
    $this->session = $session;
    if(empty($this->session->get('report_query')))
      $this->init_default_params();
    else
      $this->params = $this->session->get('report_query');
  }

  public function clear_filters(){
    $this->init_default_params();
    $filters = $this->get_filters();
    return $this->twig->render('report\clear_filter_query.tpl', ['filters' => $filters]);
  }

  public function default_page(){
    $work_types = $this->em->getRepository('domain\workgroup')
                           ->findAll(['name' => 'ASC']);
    $departments = $this->em->getRepository('domain\department')
                            ->findAll(['name' => 'ASC']);
    $streets = $this->em->getRepository('domain\street')
                        ->findAll(['name' => 'ASC']);
    $query_types = $this->em->getRepository('domain\query_type')
                            ->findAll(['name' => 'ASC']);
    $filters = $this->get_filters();
    $houses = [];
    if(!empty($filters['street']))
      $houses = $this->em->getRepository('domain\house')
                         ->findByStreet($filters['street']);
    return $this->twig->render('report\get_query_reports.tpl',
                                [
                                 'filters' => $filters,
                                 'query_work_types' => $work_types,
                                 'query_types' => $query_types,
                                 'departments' => $departments,
                                 'streets' => $streets,
                                 'houses' => $houses
                                ]);
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

  public function report1(){
    $queries = $this->em->getRepository('domain\query')
                        ->findByParams($this->params);
    return $this->twig->render('report\report_query_one.tpl', ['queries' => $queries]);
  }

  public function report1_xls(){
    $queries = $this->em->getRepository('domain\query')
                        ->findByParams($this->params);
    $response = new Response();
    $response->setContent($this->twig->render('report\report_query_one_xls.tpl', ['queries' => $queries]));
    $response->headers->set('Content-Disposition', 'attachment; filename=export.xml');
    $response->headers->set('Content-type', 'application/octet-stream');
    return $response;
  }

  public function save_params(array $params){
    foreach($params as $param => $value)
      if(array_key_exists($param, $this->params))
        $this->params[$param] = $value;
    $this->session->set('report_query', $this->params);
  }

  public function set_department($id){
    $department = $this->em->find('domain\department', $id);
    $params['departments'] = ($department)? [$department->get_id()]: [];
    $params['streets'] = [];
    $params['houses'] = [];
    $this->save_params($params);
    return new Response();
  }

  public function set_house($id){
    $house = $this->em->find('domain\house', $id);
    if($house)
      $this->save_params(['houses' => [$house->get_id()]]);
    else
      $this->set_street($this->params['streets'][0]);
    return new Response();
  }

  public function set_query_type($id){
    $query_type = $this->em->find('domain\query_type', $id);
    $type = ($query_type)? [$query_type->get_id()]: [];
    $this->save_params(['query_types' => $type]);
    return new Response();
  }

  public function set_time_begin($time){
    $this->save_params(['time_begin' => strtotime($time)]);
    return new Response();
  }

  public function set_time_end($time){
    $this->save_params(['time_end' => strtotime($time) + 86400]);
    return new Response();
  }

  public function set_status($status){
    $check = in_array($status, query::$status_list, true);
    $status = ($check)? [$status]: query::$status_list;
    $this->save_params(['status' => $status]);
    return new Response();
  }

  public function set_street($id){
    $streets = $this->em->getRepository('domain\street')
                        ->findAll();
    $street_id_list = [];
    foreach($streets as $street)
      $street_id_list[] = $street->get_id();
    if(!in_array((int) $id, $street_id_list, true)){
      $params['departments'] = [];
      $params['streets'] = [];
      $params['houses'] = [];
    }else{
      $houses = $this->em->getRepository('domain\house')
                         ->findByStreet($id);
      $house_id_list = [];
      foreach($houses as $house)
        $house_id_list[] = $house->get_id();
      $params['departments'] = [];
      $params['streets'] = [$id];
      $params['houses'] = $house_id_list;
    }
    $this->save_params($params);
    $street = $this->em->find('domain\street', $id);
    return $this->twig->render('report\set_filter_query_street.tpl', ['street' => $street]);
  }

  public function set_worktype($id){
    $workgroup = $this->em->find('domain\workgroup', $id);
    $type = ($workgroup)? [$workgroup->get_id()]: [];
    $this->save_params(['work_types' => $type]);
    return new Response();
  }
}