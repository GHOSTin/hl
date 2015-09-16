<?php namespace main\models;

use RuntimeException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use domain\user;

class query_type{

  private $em;
  private $user;

  public function __construct(EntityManager $em, user $user, $id){
    $this->em = $em;
    $this->user = $user;
    if(!$this->user->check_access('system/general_access'))
      throw new RuntimeException();
    $this->type = $this->em->find('domain\query_type', $id);
    if(is_null($this->type))
      throw new RuntimeException();
  }

  public function update_color($color){
    $this->type->set_color($color);
    $this->em->flush();
    return new Response();
  }
}