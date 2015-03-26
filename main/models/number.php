<?php namespace main\models;

use Doctrine\ORM\EntityManager;

class number{

  private $em;

  public function __construct(EntityManager $em){
    $this->em = $em;
  }

  public function get_houses_by_street($street_id){
    $houses = $this->em->getRepository('domain\house')
                       ->findByStreet($street_id);
    natsort($houses);
    return $houses;
  }
}