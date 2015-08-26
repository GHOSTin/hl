<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;

class system_search{

  private $user;
  private $twig;
  private $em;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    if(!$user->check_access('system/general_access'))
      throw new RuntimeException('ACCESS DENIED');
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
  }

  public function search_number($search){
    $number = $this->em->getRepository('domain\number')->findOneByNumber($search);
    return $this->twig->render('system/search_number.tpl',
                                [
                                 'user' => $this->user,
                                 'search' => $search,
                                 'number' => $number
                                ]);
  }

  public function search_number_form(){
    return $this->twig->render('system/search_number_form.tpl', ['user' => $this->user]);
  }
}