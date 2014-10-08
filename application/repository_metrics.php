<?php

class repository_metrics extends \Doctrine\ORM\EntityRepository {

  public function findByStatusBetween($status, $time){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('data_metrics', 'm')
      ->where($qb->expr()->andX(
          $qb->expr()->eq('m.status', ':status'),
          $qb->expr()->gte("m.time", ':start_date'),
          $qb->expr()->lte("m.time", ':end_date')
      ))
      ->orderBy('m.time', 'ASC')
      ->setParameter('status', $status)
      ->setParameter('start_date', strtotime($time.' midnight'))
      ->setParameter('end_date', strtotime($time.' +1 day midnight'));

    return $qb->getQuery()->getResult();
  }

} 