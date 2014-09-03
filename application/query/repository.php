<?php
class repository_query extends Doctrine\ORM\EntityRepository {

  public function findByParams(array $params){
    $query =  $this->_em->createQuery('SELECT q FROM data_query q
      WHERE q.time_open > :time_open_begin AND q.time_open < :time_open_end
      AND q.status IN(:status) ORDER BY q.time_open');
    $query->setParameters($params);
    return $query->getResult();
  }
}
