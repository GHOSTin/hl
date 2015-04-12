<?php namespace main\models;

use Doctrine\ORM\EntityManager;
use domain\user;

class number{

  private $em;
  private $user;

  public function __construct(EntityManager $em, user $user){
    $this->em = $em;
    $this->user = $user;
  }

  public function get_houses_by_street($street_id){
    $args['street'] = $street_id;
    if(!empty($this->user->get_restriction('departments')))
      $args['department'] = $this->user->get_restriction('departments');
    $houses = $this->em->getRepository('domain\house')
                       ->findBy($args);
    natsort($houses);
    return $houses;
  }

  public function get_streets(){
    $args = [];
    $streets = [];
    if(!empty($this->user->get_restriction('departments')))
      $args['department'] = $this->user->get_restriction('departments');
    $houses = $this->em->getRepository('domain\house')
                       ->findBy($args);
    foreach($houses as $house){
      $street = $house->get_street();
      if(!isset($streets[$street->get_id()]))
        $streets[$street->get_id()] = $street;
    }
    natsort($streets);
    return $streets;
  }
}