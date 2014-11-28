<?php namespace domain\repositories;

use \Doctrine\ORM\EntityRepository;

class query extends EntityRepository{

  public function findByParams(array $params){

    $qb = $this->_em->createQueryBuilder();
    $qb->select('q')
       ->from('\domain\query', 'q')
       ->where($qb->expr()->andX(
          $qb->expr()->gt('q.time_open', ':time_open_begin'),
          $qb->expr()->lt('q.time_open', ':time_open_end'),
          $qb->expr()->in('q.status', ':status')
        ))
       ->orderBy('q.time_open', 'DESC')
       ->setParameters(array(
          'time_open_begin'=> $params['time_begin'],
          'time_open_end' => $params['time_end'],
          'status' => $params['status']
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
