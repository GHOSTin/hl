<?php

class repository_task extends Doctrine\ORM\EntityRepository {

  public function findActiveTask(){
    return $this->findBy(array('status'=>array('open', 'reopen')));
  }
} 