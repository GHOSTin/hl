<?php namespace domain\repositories;

use domain\user;

class task extends \Doctrine\ORM\EntityRepository {

  private static $active_task = "SELECT t FROM domain\\task t JOIN t.performers p WHERE t.status IN ('open', 'reopen') AND (p = :user OR t.creator = :user)";
  private static $close_task = "SELECT t FROM domain\\task t JOIN t.performers p WHERE t.status = 'close' AND (p = :user OR t.creator = :user)";

  public function findActiveTask(user $user){
    $query = $this->_em->createQuery(self::$active_task);
    $query->setParameter(':user', $user);
    $tasks = $query->getResult();
    return $tasks;
  }

  public function findCloseTask(user $user){
    $query = $this->_em->createQuery(self::$close_task);
    $query->setParameter(':user', $user);
    $tasks = $query->getResult();
    return $tasks;
  }

  public function getInsertId(){
    $prefix = date('Ymd');
    $query = $this->_em->createQuery('SELECT MAX(t.id) FROM \domain\task t WHERE t.id > :id');
    $query->setParameter(':id', $prefix.'000000');
    $id = $query->getSingleScalarResult();
    if(is_null($id))
      return $prefix.'000001';
    else
      return $id + 1;
  }
}