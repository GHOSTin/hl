<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use domain\group;

class groups{

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

  public function create_group($name){
    $exists = $this->em->getRepository('domain\group')
                       ->findOneByName($name);
    if(!is_null($exists))
      throw new RuntimeException('Группа с таким название уже существует.');
    $group = new group();
    $group->set_name($name);
    $this->em->persist($group);
    $this->em->flush();
    return $group;
  }

  public function get_dialog_create_group(){
    return $this->twig->render('user\get_dialog_create_group.tpl');
  }

  public function get_groups(){
    $groups = $this->em->getRepository('domain\group')
                       ->findBy([], ['name' => 'ASC']);
    return $this->twig->render('user\get_groups.tpl', ['groups' => $groups]);
  }
}