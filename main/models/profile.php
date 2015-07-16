<?php namespace main\models;

use RuntimeException;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use domain\user;

class profile{

  private $em;
  private $twig;
  private $user;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
  }

  public function default_page(){
    return $this->twig->render('profile\default_page.tpl', ['user' => $this->user]);
  }

  public function get_user_info(){
    return $this->twig->render('profile\get_userinfo.tpl', ['user' => $this->user]);
  }

  public function update_cellphone($cellphone){
    $this->user->set_cellphone($cellphone);
    $this->em->flush();
    return $this->twig->render('profile\get_userinfo.tpl', ['user' => $this->user]);
  }

  public function update_password($password, $confirm, $salt){
    if($password !== $confirm)
      throw new RuntimeException('Password problem.');
    $this->user->set_hash(user::generate_hash($password, $salt));
    $this->em->flush();
    return $this->twig->render('profile\get_userinfo.tpl', ['user' => $this->user]);
  }

  public function update_telephone($telephone){
    $this->user->set_telephone($telephone);
    $this->em->flush();
    return $this->twig->render('profile\get_userinfo.tpl', ['user' => $this->user]);
  }
}