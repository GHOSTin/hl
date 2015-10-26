<?php namespace main\models;

use RuntimeException;
use Doctrine\ORM\EntityManager;
use domain\user;

class street{

  private $em;
  private $user;

  public function __construct(EntityManager $em, user $user, $id){
    $this->em = $em;
    $this->user = $user;
    $this->street = $this->em->find('domain\street', $id);
    if(is_null($this->street))
      throw new RuntimeException();
  }

  public function get_street(){
    return $this->street;
  }

  public function get_houses(){
    $args['street'] = $this->street->get_id();
    if(!empty($this->user->get_restriction('departments')))
      $args['department'] = $this->user->get_restriction('departments');
    $houses = $this->em->getRepository('domain\house')
                       ->findBy($args);
    natsort($houses);
    return $houses;
  }
}