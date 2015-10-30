<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use Symfony\Component\HttpFoundation\Response;

class phrase{

  private $em;
  private $twig;
  private $user;
  private $phrase;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user, $id){
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
    if(!$this->user->check_access('system/general_access'))
      throw new RuntimeException();
    $this->phrase = $this->em->find('domain\phrase', $id);
    if(is_null($this->phrase))
      throw new RuntimeException();
  }

  public function remove_phrase_dialog(){
    return $this->twig->render('phrase\remove_phrase_dialog.tpl', ['phrase' => $this->phrase]);
  }

  public function remove(){
    $this->em->remove($this->phrase);
    $this->em->flush();
    return new Response();
  }
}