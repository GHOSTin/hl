<?php namespace main\models;

use RuntimeException;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use domain\user;
use DateTime;

class report_outages{

  private $em;
  private $twig;
  private $user;
  private $params = [
                     'start' => 0,
                     'end' => 0,
                    ];

  public function __construct(EntityManager $em, Twig_Environment $twig, user $user, Session $session){
    $this->em = $em;
    $this->twig = $twig;
    $this->user = $user;
    $this->session = $session;
    if(!$user->check_access('reports/general_access'))
      throw new RuntimeException();
    if(empty($this->session->get('report_outages')))
      $this->init_default_params();
    else
      $this->params = $this->session->get('report_outages');
  }

  public function init_default_params(){
    $this->save_params([
                        'start' => strtotime('midnight'),
                        'end' => strtotime('tomorrow'),
                       ]);
  }

  public function default_page(){
    return $this->twig->render('report_outages\default_page.tpl', ['filters' => $this->get_filters()]);
  }

  public function html(){
    $outages = $this->em->getRepository('domain\outage')
                        ->findByParams($this->params);
    return $this->twig->render('report_outages\html.tpl', [
                                                            'outages' => $outages,
                                                            'user' => $this->user
                                                          ]);
  }

  public function start($start){
    $time = DateTime::createFromFormat('H:i d.m.Y', $start);
     $this->save_params(['start' => $time->getTimestamp()]);
    return new Response();
  }

  public function end($start){
    $time = DateTime::createFromFormat('H:i d.m.Y', $start);
     $this->save_params(['end' => $time->getTimestamp()]);
    return new Response();
  }

  public function save_params(array $params){
    foreach($params as $param => $value)
      if(array_key_exists($param, $this->params))
        $this->params[$param] = $value;
    $this->session->set('report_outages', $this->params);
  }

  public function get_filters(){
    return $this->params;
  }
}