<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use main\models\street;

class numbers{

  private $em;
  private $twig;
  private $user;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user){
    if(!$user->check_access('numbers/general_access'))
      throw new RuntimeException();
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
  }


  public function default_page(){
    return $this->twig->render('number\default_page.tpl',
                                [
                                 'user' => $this->user,
                                 'streets' => $this->get_streets()
                                ]);
  }

  public function get_streets(){
    $args = [];
    $streets = [];
    if(!empty($this->user->get_restriction('departments')))
      $args['department'] = $this->user->get_restriction('departments');
    $houses = $this->em->getRepository('domain\house')
                       ->findBy($args);
    foreach($houses as $house){
      $street = $house->get_street();
      if(!isset($streets[$street->get_id()]))
        $streets[$street->get_id()] = $street;
    }
    natsort($streets);
    return $streets;
  }

  public function get_street_content(street $model){
    $res['workspace'] = $this->twig->render('number\build_houses_titles.tpl',
                                          [
                                           'houses' => $model->get_houses()
                                          ]);
    $res['path'] = $this->twig->render('number\get_street_content.tpl',
                                          [
                                           'street' => $model->get_street()
                                          ]);
    return $res;
  }

  public function get_house_content($id){
    $house = $this->em->find('domain\house', $id);
    $res['workspace'] = $this->twig->render('number/build_house_content.tpl', ['house' => $house]);
    $res['path'] = $this->twig->render('number\get_house_content.tpl', ['house' => $house]);
    return $res;
  }

  public function get_number_content($id){
    $number = $this->em->find('domain\number', $id);
    $res['workspace'] = $this->twig->render('number/build_number_fio.tpl',
                                [
                                 'number' => $number,
                                 'user' => $this->user
                                ]);
    $res['path'] = $this->twig->render('number\get_number_content.tpl', ['number' => $number]);
    return $res;
  }

  public function streets(){
    $res['workspace'] =  $this->twig->render('number\build_street_titles.tpl', ['streets' => $this->get_streets()]);
    return $res;
  }

}