<?php namespace domain\repositories;

use DateTime;

class outage extends \Doctrine\ORM\EntityRepository {

  public function active(){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('domain\outage', 'm')
      ->where($qb->expr()->gte("m.target", ':target'))
      ->orderBy('m.begin', 'DESC')
      ->setParameter('target', strtotime('today'));;
    return $qb->getQuery()->getResult();
  }

  public function findByBeginTimeRange($start, $end){
    $start = DateTime::createFromFormat('H:i d.m.Y', $start);
    $end = DateTime::createFromFormat('H:i d.m.Y', $end);
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('domain\outage', 'm')
      ->where($qb->expr()->andX(
          $qb->expr()->gte("m.begin", ':begin_start'),
          $qb->expr()->lt("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'DESC')
      ->setParameter('begin_start', $start->getTimestamp())
      ->setParameter('begin_end', $end->getTimestamp());
    return $qb->getQuery()->getResult();
  }


  public function yesterday(){
    $qb = $this->_em->createQueryBuilder();
    $qb->select('m')
      ->from('domain\outage', 'm')
      ->where($qb->expr()->andX(
          $qb->expr()->gte("m.begin", ':begin_start'),
          $qb->expr()->lt("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'DESC')
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
          $qb->expr()->lt("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'DESC')
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
          $qb->expr()->lt("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'DESC')
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
          $qb->expr()->lt("m.begin", ':begin_end')
      ))
      ->orderBy('m.begin', 'DESC')
      ->setParameter('begin_start', strtotime('monday last week'))
      ->setParameter('begin_end', strtotime('monday this week'));
    return $qb->getQuery()->getResult();
  }
}