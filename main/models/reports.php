<?php namespace main\models;

use Silex\Application;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use domain\user;
use RuntimeException;

class reports{

  private $app;
  private $em;
  private $twig;
  private $user;

  public function __construct(Application $app, Twig_Environment $twig, EntityManager $em, user $user){
    if(!$user->check_access('reports/general_access'))
      throw new RuntimeException();
    $this->twig = $twig;
    $this->app = $app;
    $this->em = $em;
    $this->user = $user;
  }

  public function default_page(){
    return $this->twig->render('report\default_page.tpl', ['user' => $this->user]);
  }
}