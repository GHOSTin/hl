<?php namespace domain\repositories;

use Doctrine\ORM\EntityRepository;

class number2event extends EntityRepository{

  public function findByIndex($time, $number_id, $event_id){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('e')
      ->from('domain\number2event', 'e')
      ->where($qb->expr()->andX(
          $qb->expr()->eq('e.time', ':time'),
          $qb->expr()->eq('e.number', ':number_id'),
          $qb->expr()->eq('e.event', ':event_id')
      ))
      ->orderBy('e.time', 'DESC')
      ->setParameter('time', $time)
      ->setParameter('number_id', $number_id)
      ->setParameter('event_id', $event_id);
    return $qb->getQuery()->getResult();
  }

  public function findByParams(array $params){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('n2e')
       ->from('domain\number2event', 'n2e')
       ->where($qb->expr()->andX(
          $qb->expr()->gt('n2e.time', ':time_begin'),
          $qb->expr()->lt('n2e.time', ':time_end')
       ))
       ->orderBy('n2e.time', 'DESC')
       ->setParameters(array(
          'time_begin' => $params['time_begin'],
          'time_end' => $params['time_end']
       ));
    $query = $qb->getQuery();
    return $query->getResult();
  }
}