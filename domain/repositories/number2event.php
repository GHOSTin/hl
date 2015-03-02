<?php namespace domain\repositories;

class number2event extends \Doctrine\ORM\EntityRepository {

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
}