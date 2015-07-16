<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\meterage;
use domain\user;

class import_meterages{

  private $em;
  private $rows = [];

  const MEMORY_LIMIT = 8*1024*1024;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    $this->em = $em;
    $this->twig = $twig;
    $this->user = $user;
  }

  public function load_meterages($hndl, $date){
    $time = strtotime('12:00 01.'.$date);
    while($row = fgetcsv($hndl, 0, ';')){
      $number = $this->em->getRepository('domain\number')
                         ->findOneByNumber($row[0]);
      if($number){
        $this->em->persist(meterage::new_instance($number, $time, $row[1], $row[2], $row[3], $row[4]));
      }else
        $this->rows[] = $row;
      if(memory_get_usage() > self::MEMORY_LIMIT){
        $this->em->flush();
        $this->em->clear();
      }
    }
    $this->em->flush();
    $this->em->clear();
    return $this->twig->render('import\load_meterages.tpl', ['user' => $this->user, 'rows' => $this->rows]);
  }
}