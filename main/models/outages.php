<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;
use main\model\workgroup as workgroup_model;
use domain\outage;

class outages{

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

  public function active(){
    $outages = $this->em->getRepository('domain\outage')->active();
    return [
            'outages' => $this->twig->render('outages/outages.tpl', ['outages' => $outages])
           ];
  }

  public function create($begin, $target, $type, $houses = [], $performers = [], $description){
    $houses = $this->em->getRepository('domain\house')->findById($houses);
    $performers = $this->em->getRepository('domain\user')->findById($performers);
    $workgroup = $this->em->getRepository('domain\workgroup')->findOneById($type);
    $outage = outage::new_instance($begin, $target, $workgroup, $this->user, $houses, $performers, $description);
    $this->em->persist($outage);
    $this->em->flush();
  }

  public function default_page(){
    $outages = $this->em->getRepository('domain\outage')->active();
    return [
            'workspace' => $this->twig->render('outages/default_page.tpl', ['outages' => $outages])
           ];
  }

  public function dialog_create(){
    $workgroups = $this->em->getRepository('domain\workgroup')->findBy([], ['name' => 'ASC']);
    $streets = $this->em->getRepository('domain\street')->findBy([], ['name' => 'ASC']);
    $groups = $this->em->getRepository('domain\group')->findBy([], ['name' => 'ASC']);
    return $this->twig->render('outages/dialog_create.tpl', [
                                                              'workgroups' => $workgroups,
                                                              'streets' => $streets,
                                                              'groups' => $groups
                                                            ]);
  }

  public function houses($id){
    $street = $this->em->getRepository('domain\street')->findOneById($id);
    return $this->twig->render('outages/houses.tpl', ['street' => $street]);
  }

  public function today(){
    $outages = $this->em->getRepository('domain\outage')->today();
    return [
            'outages' => $this->twig->render('outages/outages.tpl', ['outages' => $outages])
           ];
  }

  public function yesterday(){
    $outages = $this->em->getRepository('domain\outage')->yesterday();
    return [
            'outages' => $this->twig->render('outages/outages.tpl', ['outages' => $outages])
           ];
  }

  public function week(){
    $outages = $this->em->getRepository('domain\outage')->week();
    return [
            'outages' => $this->twig->render('outages/outages.tpl', ['outages' => $outages])
           ];
  }

  public function lastweek(){
    $outages = $this->em->getRepository('domain\outage')->lastweek();
    return [
            'outages' => $this->twig->render('outages/outages.tpl', ['outages' => $outages])
           ];
  }

  public function users($id){
    $group = $this->em->getRepository('domain\group')->findOneById($id);
    return $this->twig->render('outages/users.tpl', ['group' => $group]);
  }
}