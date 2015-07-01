<?php namespace main\models;

use Silex\Application;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use domain\user;

class factory{

  private $app;
  private $em;
  private $twig;
  private $user;

  public function __construct(Application $app, Twig_Environment $twig, EntityManager $em, user $user){
    $this->twig = $twig;
    $this->app = $app;
    $this->em = $em;
    $this->user = $user;
  }

  public function get_number_model($id){
    return new number5($this->app, $this->twig, $this->em, $this->user, $id);
  }

  public function get_reports_model(){
    return new reports($this->app, $this->twig, $this->em, $this->user);
  }

  public function get_report_queries_model(){
    return new report_queries($this->app, $this->twig, $this->em, $this->user, $this->app['session']);
  }
}