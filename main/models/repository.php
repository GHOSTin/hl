<?php namespace main\models;

use Doctrine\ORM\EntityManager;
use domain\user;
use RuntimeException;

class repository{

  private $em;
  private $user;

  public function __construct(EntityManager $em, user $user){
    $this->em = $em;
    $this->user = $user;
  }

  public function get_workgroup($id){
    $workgroup = $this->em->find('domain\workgroup', $id);
    if(!$workgroup)
      throw new RuntimeException('Workgroup id='.$id.' not exists');
    return $workgroup;
  }
}