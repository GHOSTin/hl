<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use domain\phrase;

class workgroup{

  private $em;
  private $twig;
  private $user;
  private $workgroup;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user, $id){
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
    if(!$this->user->check_access('system/general_access'))
      throw new RuntimeException();
    $this->workgroup = $this->em->find('domain\workgroup', $id);
    if(is_null($this->workgroup))
      throw new RuntimeException();
  }

  public function create_phrase_dialog(){
    return $this->twig->render('workgroup\create_phrase_dialog.tpl', ['workgroup' => $this->workgroup]);
  }

  public function create_phrase($text){
    $phrase = phrase::new_instance($this->workgroup, $text);
    $this->em->persist($phrase);
    $this->em->flush();
  }

  public function get_content(){
    return $this->twig->render('works\get_workgroup_content.tpl', ['workgroup' => $this->workgroup]);
  }
}