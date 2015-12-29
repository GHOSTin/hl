<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use DateTime;

class events{

  private $em;
  private $twig;
  private $user;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    if(!$user->check_access('numbers/general_access'))
      throw new RuntimeException();
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
  }

  public function get_day_events($date){
    $time = DateTime::createFromFormat('H:i d-m-Y', '12:00 '.$date);
    $events = $this->em->getRepository('domain\number2event')
                       ->findByTime($time->getTimeStamp());
    return $events;
  }

  public function get_dialog_create_event(){
    $streets = $this->em->getRepository('domain\street')
        ->findBy([], ['name' => 'ASC']);
    $workgroups = $this->em->getRepository('domain\workgroup')
        ->findBy([], ['name' => 'ASC']);
    return  $this->twig->render('events/get_dialog_create_event.tpl', ['streets' => $streets, 'workgroups'=>$workgroups]);
  }

  public function houses($id){
    $street = $this->em->getRepository('domain\street')->findOneById($id);
    return $this->twig->render('events/houses.tpl', ['street' => $street]);
  }

  public function numbers($id){
    $house = $this->em->getRepository('domain\house')->findOneById($id);
    return $this->twig->render('events/numbers.tpl', ['house' => $house]);
  }

  public function create_event($number_id, $event_id, $date, $comment, $files){
    $number = $this->em->find('domain\number', $number_id);
    $event = $this->em->find('domain\event', $event_id);
    $n2e = $number->add_event($event, $date, $comment);
    if(!empty($files)){
      foreach($files as $fr){
        $file = $this->em->find('domain\file', $fr['url']);
        if($file)
          $n2e->add_file($file);
      }
    }
    $this->em->flush();
    return $n2e;

  }
}