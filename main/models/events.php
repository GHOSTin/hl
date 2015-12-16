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

  public function default_page(){
    $events = $this->em->getRepository('domain\number2event')
                       ->findByTime(strtotime('12:00'));
    return [
            'workspace' => $this->twig->render('events/default_page.tpl',
              [
                'events' => $events,
                'user' => $this->user
              ])
           ];
  }

  public function get_day_events($date){
    $time = DateTime::createFromFormat('H:i d-m-Y', '12:00 '.$date);
    $events = $this->em->getRepository('domain\number2event')
                       ->findByTime($time->getTimeStamp());
    return [
        'workspace' => $this->twig->render('events/events.tpl',
            [
                'events' => $events,
                'user' => $this->user
            ])
    ];
  }
}