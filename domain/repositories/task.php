<?php namespace domain\repositories;

use \domain\user;

class task extends \Doctrine\ORM\EntityRepository {

  public function findActiveTask(user $user){
    $tasks = $this->findBy(array('status'=>array('open', 'reopen')));
    $result = [];
    foreach ($tasks as $task) {
      if($task->isPerson($user)){
        $result[] = $task;
      }
    }
    return $result;
  }

  public function findCloseTask(user $user){
    $tasks = $this->findBy(array('status'=>array('close')));
    $result = [];
    foreach ($tasks as $task) {
      if($task->isPerson($user)){
        $result[] = $task;
      }
    }
    return $result;
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