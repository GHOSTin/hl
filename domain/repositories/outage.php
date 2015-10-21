<?php namespace domain\repositories;

class outage extends \Doctrine\ORM\EntityRepository {

  public function yesterday(){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('domain\outage', 'm')
      ->where($qb->expr()->andX(
          $qb->expr()->gte("m.begin", ':begin_start'),
          $qb->expr()->lte("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'ASC')
      ->setParameter('begin_start', strtotime('yesterday'))
      ->setParameter('begin_end', strtotime('today'));
    return $qb->getQuery()->getResult();
  }

  public function today(){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('domain\outage', 'm')
      ->where($qb->expr()->andX(
          $qb->expr()->gte("m.begin", ':begin_start'),
          $qb->expr()->lte("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'ASC')
      ->setParameter('begin_start', strtotime('today'))
      ->setParameter('begin_end', strtotime('tomorrow'));
    return $qb->getQuery()->getResult();
  }


  public function week(){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('domain\outage', 'm')
      ->where($qb->expr()->andX(
          $qb->expr()->gte("m.begin", ':begin_start'),
          $qb->expr()->lte("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'ASC')
      ->setParameter('begin_start', strtotime('monday this week'))
      ->setParameter('begin_end', strtotime('monday next week'));
    return $qb->getQuery()->getResult();
  }

  public function lastweek(){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('domain\outage', 'm')
      ->where($qb->expr()->andX(
          $qb->expr()->gte("m.begin", ':begin_start'),
          $qb->expr()->lte("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'ASC')
      ->setParameter('begin_start', strtotime('monday last week'))
      ->setParameter('begin_end', strtotime('monday this week'));
    return $qb->getQuery()->getResult();
  }
}