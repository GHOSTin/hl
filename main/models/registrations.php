<?php namespace main\models;

use RuntimeException;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use Monolog\Logger;
use domain\user;

class registrations{

  private $em;
  private $twig;
  private $logger;

  public function __construct(Twig_Environment $twig, EntityManager $em,user $user, Logger $logger){
    if(!$user->check_access('system/general_access'))
      throw new RuntimeException('ACCESS DENIED');
    $this->em = $em;
    $this->twig = $twig;
    $this->user = $user;
    $this->logger = $logger;
  }

  /**
   * Выводит начальную страницу раздела запросов на регистрацию в личном кабинете
   */
  public function default_page(){
    return $this->twig->render('registrations/default_page.tpl', ['user' => $this->user]);
  }

  /**
   * Выдает список запросов
   */
  public function get_open_requests(){
    return $this->em->getRepository('domain\registration_request')
                    ->findBy([]);
  }
}