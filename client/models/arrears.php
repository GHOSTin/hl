<?php namespace client\models;

use Doctrine\ORM\EntityManager;
use Twig_Environment;


class arrears{

  private $em;
  private $twig;

  public function __construct(Twig_Environment $twig, EntityManager $em){
    $this->em = $em;
    $this->twig = $twig;
  }

  public function default_page(){
    $streets = $this->em->getRepository('domain\street')
                        ->findBy([], ['name' => 'ASC']);
    return $this->twig->render('arrears/default_page.tpl', ['streets' => $streets]);
  }

  public function flat($id){
    $flat = $this->em->getRepository('domain\flat')
                     ->find($id);
    return $this->twig->render('arrears/flat.tpl', ['flat' => $flat]);
  }

  public function flats($id){
    $house = $this->em->getRepository('domain\house')
                       ->find($id);
    return $this->twig->render('arrears/flats.tpl', ['house' => $house]);
  }

  public function houses($id){
    $street = $this->em->getRepository('domain\street')
                       ->find($id);
    return $this->twig->render('arrears/houses.tpl', ['street' => $street]);
  }

  public function top($id, $debt_limit){
    $house = $this->em->getRepository('domain\house')
                      ->find($id);
    $debtors = $house->get_debtors($debt_limit);
    krsort($debtors);
    return $this->twig->render('arrears/top.tpl', ['debtors' => $debtors]);
  }
}