<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use domain\api_key as key;

class api_key{

  private $em;
  private $user;
  private $twig;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    if(!$user->check_access('system/api_key'))
      throw new RuntimeException('ACCESS DENIED');
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
  }

  public function create($name){
    $exists_key = $this->em->getRepository('domain\api_key')
                           ->findByName(trim($name));
    if($exists_key)
      throw new RuntimeException('Duplicate name');
    $key = key::new_instance($name);
    $this->em->persist($key);
    $this->em->flush();
    return $this->twig->render('api_keys/keys.tpl', ['keys' => $this->get_keys()]);
  }

  public function create_dialog(){
    return $this->twig->render('api_keys/create_dialog.tpl');
  }

  public function default_page(){
    return $this->twig->render('api_keys/default_page.tpl',
                                [
                                 'keys' => $this->get_keys(),
                                 'user' => $this->user
                                ]);
  }

  public function get_keys(){
    return $this->em->getRepository('domain\api_key')
                    ->findBy([], ['name' => 'ASC']);
  }
}