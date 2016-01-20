<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;

class users{

  private $em;
  private $twig;
  private $user;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    if(!$user->check_access('system/general_access'))
      throw new RuntimeException();
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
  }

  public function create_user($lastname, $firstname, $middlename, $login, $hash){
    $exists = $this->em->getRepository('domain\user')
                       ->findOneByLogin($login);
    if($exists)
      throw new RuntimeException();
    $user = user::create($lastname, $firstname, $middlename, $login, $hash);
    $this->em->persist($user);
    $this->em->flush();
    return $user;
  }

  public function default_page(){
    return $this->twig->render('user\default_page.tpl', ['user' => $this->user]);
  }

  public function get_dialog_create_user(){
    return $this->twig->render('user\get_dialog_create_user.tpl');
  }

  public function get_users(){
    $users = $this->em->getRepository('domain\user')
                      ->findBy([], ['lastname' => 'ASC']);
    return $this->twig->render('user\get_users.tpl', ['users' => $users]);
  }
}