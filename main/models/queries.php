<?php namespace main\models;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig_Environment;
use domain\query;
use domain\user;
use domain\house;

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

  public function __construct(EntityManager $em, Session $session, user $user, Twig_Environment $twig){
    $this->em = $em;
    $this->user = $user;
    $this->session = $session;
    $this->twig = $twig;
    if(empty($this->session->get('query'))){
      $this->init_default_params();
    }else
      $this->params = $this->session->get('query');
  }

  public function abort_query_from_request($description, $time, $category_id, $query_type_id, $number_id){
    $request = $this->get_request($number_id, $time);
    $args['query_type'] = $this->em->find('domain\query_type', $query_type_id);
    $args['category'] = $this->em->find('domain\workgroup', $category_id);
    $args['description'] = $request->get_message();
    $args['number'] = $this->generate_next_number();
    $args['contact_fio'] = null;
    $args['contact_telephone'] = null;
    $args['contact_cellphone'] = null;
    $query = query::new_instance_from_request($request, $args);
    $query->set_creator($this->user);
    $query->add_manager($this->user);
    $query->close($query->get_time_open(), $description);
    $this->em->persist($query);
    $this->em->flush();
    return $this->twig->render('query\query_titles.tpl', ['queries' => $this->get_today_queries()]);
  }

  public function abort_query_from_request_dialog($number, $time){
    $request = $this->get_request($number, $time);
    $number = $request->get_number();
    return $this->twig->render('query\abort_query_from_request_dialog.tpl',
                                [
                                 'query_work_types' => $this->get_categories(),
                                 'queries' => $this->get_query_of_house($number->get_flat()->get_house(), 5),
                                 'number' => $number,
                                 'request' => $request,
                                 'query_types' => $this->get_query_types()
                                ]);
  }

  public function create_query($description, $initiator, $category_id, $query_type_id, $contact_fio,
                                $contact_telephone, $contact_cellphone, $id){
    $args['query_type'] = $this->em->find('domain\query_type', $query_type_id);
    $args['category'] = $this->em->find('domain\workgroup', $category_id);
    $args['description'] = $description;
    $args['contact_fio'] = $contact_fio;
    $args['contact_telephone'] = $contact_telephone;
    $args['contact_cellphone'] = $contact_cellphone;
    $args['number'] = $this->generate_next_number();
    if($initiator === 'house'){
      $query = query::new_instance_house_initiator($this->em->find('domain\house', $id), $args);
    }elseif($initiator === 'number'){
      $query = query::new_instance_number_initiator($this->em->find('domain\number', $id), $args);
    }
    $query->set_creator($this->user);
    $query->add_manager($this->user);
    $this->em->persist($query);
    $this->em->flush();
    return $this->twig->render('query\query_titles.tpl', ['queries' => $this->get_today_queries()]);
  }

  public function create_query_from_request($description, $time, $category_id, $query_type_id, $number_id){
    $request = $this->get_request($number_id, $time);
    $args['query_type'] = $this->em->find('domain\query_type', $query_type_id);
    $args['category'] = $this->em->find('domain\workgroup', $category_id);
    $args['description'] = $description;
    $args['number'] = $this->generate_next_number();
    $args['contact_fio'] = null;
    $args['contact_telephone'] = null;
    $args['contact_cellphone'] = null;
    $query = query::new_instance_from_request($request, $args);
    $query->set_creator($this->user);
    $query->add_manager($this->user);
    $this->em->persist($query);
    $this->em->flush();
    return $this->twig->render('query\query_titles.tpl', ['queries' => $this->get_today_queries()]);
  }

  public function create_query_from_request_dialog($number, $time){
    $request = $this->get_request($number, $time);
    $number = $request->get_number();
    return $this->twig->render('query\create_query_from_request_dialog.tpl',
                                [
                                 'query_work_types' => $this->get_categories(),
                                 'queries' => $this->get_query_of_house($number->get_flat()->get_house(), 5),
                                 'number' => $number,
                                 'request' => $request,
                                 'query_types' => $this->get_query_types()
                                ]);
  }

  public function generate_next_number(){
    $time = getdate();
    $connection = $this->em->getConnection();
    $query = $connection->query('SELECT MAX(querynumber) as number FROM `queries`
                                WHERE `opentime` > '.mktime(0, 0, 0, 1, 1, $time['year']).'
                                AND `opentime` <= '.mktime(23, 59, 59, 23, 59, $time['year']));
    return $query->fetch()['number'] + 1;
  }

  public function get_today_queries(){
    return $this->em->getRepository('domain\query')
                    ->findByParams([
                                   'time_begin' => strtotime('midnight'),
                                   'time_end' => strtotime('tomorrow'),
                                   'status' => query::$status_list
                                  ]);
  }

  public function get_params(){
    return $this->params;
  }

  public function get_queries(){
    return $this->em->getRepository('domain\query')
                    ->findByParams($this->params);
  }

  public function get_day_stats(){
    $queries = $this->em->getRepository('domain\query')
                        ->findByParams($this->params);
    $res['sum'] = count($queries);
    $res['open'] = 0;
    $res['working'] = 0;
    $res['close'] = 0;
    $res['reopen'] = 0;
    foreach($queries as $query)
      $res[$query->get_status()] ++;
    return $res;
  }

  public function get_query_types(){
    return $this->em->getRepository('domain\query_type')
                    ->findAll(['name' => 'ASC']);
  }

  public function get_query_of_house(house $house, $limit = null){
    return $this->em->getRepository('domain\query')
                    ->findByHouse($house, ['id' => 'DESC'], $limit);
  }

  public function get_request($number, $time){
    return $this->em->getRepository('domain\number_request')
                    ->findOneBy([
                                  'number' => $number,
                                  'time' => $time
                                 ]);
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
    $departments = $this->user->get_restriction('departments');
    if(!empty($departments)){
      $houses = $this->em->getRepository('domain\house')
                         ->findBy([
                                   'department' => $departments,
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
    $departments = $this->user->get_restriction('departments');
    if(!empty($departments)){
      $streets = [];
      $houses = $this->em->getRepository('domain\house')
                         ->findBy(['department' => $departments]);
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