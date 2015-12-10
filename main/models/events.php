<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;

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
    $events = $this->em->getRepository('domain\number2event')->findAll();
    return [
            'workspace' => $this->twig->render('events/default_page.tpl',
              [
                'events' => $events,
                'user' => $this->user
              ])
           ];
  }
}