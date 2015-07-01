<?php namespace main\models;

use Doctrine\ORM\EntityManager;
use Twig_Environment;

class notification_center{

  private $em;
  private $twig;

  public function __construct(Twig_Environment $twig, EntityManager $em){
    $this->twig = $twig;
    $this->em = $em;
  }

  public function get_content(){
    $users = $this->em->getRepository('domain\user')
                      ->findAll(['lastname' => 'DESC']);
    return $this->twig->render('notification_center/get_content.tpl', ['users' => $users]);
  }
}