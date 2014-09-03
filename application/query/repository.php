<?php
class repository_query extends Doctrine\ORM\EntityRepository {

  public function findByParams(array $params){
    $sql[] = 'SELECT q FROM data_query q
      WHERE q.time_open > :time_open_begin AND q.time_open < :time_open_end
      AND q.status IN(:status)';
    if(!empty($params['departments']))
      $sql[] = 'AND q.department IN(:departments)';
    if(!empty($params['work_types']))
      $sql[] = 'AND q.work_type IN(:work_types)';
    $sql[] = 'ORDER BY q.time_open';
    $query =  $this->_em->createQuery(implode(' ', $sql));
    $time = getdate($params['time']);
    $query->setParameter('time_open_begin',
      mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']));
    $query->setParameter('time_open_end',
      mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']));
    $query->setParameter('status', $params['status']);
    if(!empty($params['departments']))
      $query->setParameter('departments', $params['departments']);
     if(!empty($params['work_types']))
      $query->setParameter('work_types', $params['work_types']);
    return $query->getResult();
  }
}
