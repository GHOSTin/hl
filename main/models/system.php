<?php namespace main\models;

use RuntimeException;
use ReflectionClass;
use Twig_Environment;
use domain\user;

class system{

  private $user;
  private $twig;

  public function __construct(Twig_Environment $twig, user $user){
    if(!$user->check_access('system/general_access'))
      throw new RuntimeException('ACCESS DENIED');
    $this->twig = $twig;
    $this->user = $user;
  }

  public function config(){
    if(!$this->user->check_access('system/config'))
      throw new RuntimeException('ACCESS DENIED');
    $reflection = new ReflectionClass('config\general');
    return $this->twig->render('system/config.tpl',
                                [
                                  'user' => $this->user,
                                  'conf' => $reflection->getConstants()
                                ]);
  }

  public function default_page(){
    return $this->twig->render('system/default_page.tpl', ['user' => $this->user]);
  }
}