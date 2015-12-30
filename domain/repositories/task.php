<?php namespace domain\repositories;

use \domain\user;

class task extends \Doctrine\ORM\EntityRepository {

  public function findActiveTask(user $user){
    return $this->findBy([
                            'status'=> ['open', 'reopen'],
                            'creator' => $user
                            ]);
  }

  public function findCloseTask(user $user){
    return $this->findBy([
                          'status'=> ['close'],
                          'creator' => $user
                          ]);

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