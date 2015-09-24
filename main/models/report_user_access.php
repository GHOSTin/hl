<?php namespace main\models;

use RuntimeException;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use domain\user;

class report_user_access{

  private $em;
  private $twig;

  public function __construct(EntityManager $em, Twig_Environment $twig, user $user){
    $this->em = $em;
    $this->twig = $twig;
    $this->user = $user;
    if(!$user->check_access('system/general_access'))
      throw new RuntimeException();
  }

  public function report(){
    $users = $this->em->getRepository('domain\user')
                      ->findBy([], ['lastname' => 'ASC']);
    $departments = $this->em->getRepository('domain\department')
                            ->findBy([], ['name' => 'ASC']);
    $categories = $this->em->getRepository('domain\workgroup')
                           ->findBy([], ['name' => 'ASC']);
    return $this->twig->render('report_user_access\report.tpl', [
                                                                  'users' => $users,
                                                                  'departments' => $departments,
                                                                  'categories' => $categories,
                                                                  'user' => $this->user
                                                                ]);
  }
}