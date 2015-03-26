<?php namespace main\models;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class report_event{

  private $em;
  private $session;
  private $params = [
                     'time_begin' => 0,
                     'time_end' => 0,
                    ];

  public function __construct(EntityManager $em, Session $session){
    $this->em = $em;
    $this->session = $session;
    if(empty($this->session->get('report_event')))
      $this->init_default_params();
    else
      $this->params = $this->session->get('report_event');
  }

  public function init_default_params(){
    $this->save_params([
                        'time_begin' => strtotime('midnight'),
                        'time_end' => strtotime('tomorrow'),
                       ]);
  }

  public function set_time_begin($time){
    $this->save_params(['time_begin' => $time]);
  }

  public function set_time_end($time){
    $this->save_params(['time_end' => $time]);
  }

  public function get_events(){
    return $this->em->getRepository('domain\number2event')
                    ->findByParams($this->params);
  }

  public function get_filters(){
    return [
            'time_begin' => $this->params['time_begin'],
            'time_end' => $this->params['time_end']
           ];
  }

  public function save_params(array $params){
    foreach($params as $param => $value)
      if(array_key_exists($param, $this->params))
        $this->params[$param] = $value;
    $this->session->set('report_event', $this->params);
  }
}