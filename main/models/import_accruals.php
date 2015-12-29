<?php namespace main\models;

use DateTime;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\accrual;
use domain\user;

class import_accruals{

  private $em;
  private $rows = [];

  const MEMORY_LIMIT = 64*1024*1024;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    $this->em = $em;
    $this->twig = $twig;
    $this->user = $user;
  }

  public function load_accruals($hndl, $date){
    $date = DateTime::createFromFormat('H:i d.m.Y', '12:00 01.'.$date);
    $time = $date->getTimeStamp();
    while($row = fgetcsv($hndl, 0, ';')){
      $number = $this->em->getRepository('domain\number')
                         ->findOneByNumber(trim($row[0]));
      if($number){
        $number->add_accrual($time, array_slice($row, 1));
      }else
        $this->rows[] = $row;
      if(memory_get_usage() > self::MEMORY_LIMIT){
        $this->em->flush();
        $this->em->clear();
      }
    }
    $this->em->flush();
    fclose($hndl);
    return $this->twig->render('import\load_accruals.tpl', ['rows' => $this->rows, 'user' => null]);
  }
}