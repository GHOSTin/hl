<?php
class repository_query extends Doctrine\ORM\EntityRepository {

  public function findByParams(array $params){
    $qb = $this->_em->createQueryBuilder();
    $time = getdate($params['time']);
    $qb->select('q')
       ->from('data_query', 'q')
       ->where($qb->expr()->andX(
          $qb->expr()->gt('q.time_open', ':time_open_begin'),
          $qb->expr()->lt('q.time_open', ':time_open_end'),
          $qb->expr()->in('q.status', ':status')
        ))
       ->orderBy('q.time_open')
       ->setParameters(array(
          'time_open_begin'=> mktime(0, 0, 0, $time['mon'], $time['mday'], $time['year']),
          'time_open_end' => mktime(23, 59, 59, $time['mon'], $time['mday'], $time['year']),
          'status'=> $params['status']
       ));
    if(!empty($params['departments'])){
      $qb->andWhere($qb->expr()->in('q.department', ':departments'))
        ->setParameter('departments', $params['departments']);
    }
    if(!empty($params['work_types'])){
      $qb->andWhere($qb->expr()->in('q.work_type', ':work_types'))
          ->setParameter('work_types', $params['work_types']);
    }
    if(!empty($params['houses'])){
      $qb->andWhere($qb->expr()->in('q.house', ':house'))
          ->setParameter('house', $params['houses']);
    }
    $query = $qb->getQuery();
    return $query->getResult();
  }
}
