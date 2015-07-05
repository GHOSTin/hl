<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use domain\user;

class reports{

  private $twig;
  private $user;

  public function __construct(Twig_Environment $twig, user $user){
    if(!$user->check_access('reports/general_access'))
      throw new RuntimeException();
    $this->twig = $twig;
    $this->user = $user;
  }

  public function default_page(){
    return $this->twig->render('report\default_page.tpl', ['user' => $this->user]);
  }
}