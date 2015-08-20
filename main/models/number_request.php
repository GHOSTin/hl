<?php namespace main\models;

use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;

class number_request{

  private $em;
  private $user;
  private $twig;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
  }

  public function count(){
    return $this->twig->render('query/count_requests.tpl',
                                [
                                  'number_requests' => $this->get_requests(),
                                  'user' => $this->user
                                ]);
  }

  public function get_requests(){
    return $this->em->getRepository('domain\number_request')
                    ->findByQuery(null, ['time' => 'ASC']);
  }

  public function requests(){
    return $this->twig->render('query/requests.tpl',
                                [
                                  'number_requests' => $this->get_requests(),
                                  'user' => $this->user
                                ]);
  }
}