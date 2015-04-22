<?php namespace main\models;

use Silex\Application;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;

class model{

  protected $app;
  protected $em;
  protected $twig;
  protected $user;

  public function __construct(Application $app, Twig_Environment $twig, EntityManager $em, user $user){
    $this->twig = $twig;
    $this->app = $app;
    $this->em = $em;
    $this->user = $user;
  }
}