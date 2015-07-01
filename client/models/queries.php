<?php namespace client\models;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig_Environment;
use domain\number;
use domain\number_request;

class queries{

  private $em;
  private $twig;
  private $number;
  private $time;

  public function __construct(Twig_Environment $twig, EntityManager $em, number $number){
    $this->em = $em;
    $this->number = $number;
    $this->twig = $twig;
    $this->time = time();
  }


  public function count_24_hours_number_request(){
    $qb = $this->em->createQueryBuilder();
    $qb->select('r')
       ->from('domain\number_request', 'r')
       ->where($qb->expr()->andX(
         $qb->expr()->eq('r.number', $this->number->get_id()),
         $qb->expr()->between('r.time', $this->time - 86400, $this->time)
       ));
    $query = $qb->getQuery();
    return count($query->getResult());
  }

  public function default_page(){
    return $this->twig->render('queries/default_page.tpl', [
                                                              'number' => $this->number,
                                                              'queries' => $this->get_queries(),
                                                              'requests' => $this->get_number_request(),
                                                              'count' => $this->count_24_hours_number_request()
                                                            ]);
  }

  public function get_number_request(){
    return $this->em->getRepository('domain\number_request')
                    ->findBy(
                              [
                                'query' => null,
                                'number' => $this->number
                              ],
                              ['time' => 'DESC']
                            );
  }

  public function get_queries(){
    return $this->number->get_queries()->filter(function($element){
      return $element->get_initiator() == 'number';
    });
  }

  public function request(){
    if($this->count_24_hours_number_request() > 0)
      return new RedirectResponse('/queries/');
    return $this->twig->render('queries/request.tpl', ['number' => $this->number]);
  }

  public function send_request($description){
    if($this->count_24_hours_number_request() < 1){
      $req = new number_request($this->number, $description);
      $this->em->persist($req);
      $this->em->flush();
    }
    return new RedirectResponse('/queries/');
  }
}