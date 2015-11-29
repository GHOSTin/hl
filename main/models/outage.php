<?php namespace main\models;

use RuntimeException;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use domain\user;

class outage{

  private $em;
  private $twig;
  private $user;
  private $outage;

  public function __construct(Twig_Environment $twig, EntityManager $em, user $user, $id){
    $this->twig = $twig;
    $this->em = $em;
    $this->user = $user;
    if(!$this->user->check_access('numbers/general_access'))
      throw new RuntimeException();
    $this->outage = $this->em->find('domain\outage', $id);
    if(is_null($this->outage))
      throw new RuntimeException();
  }

  public function get_edit_dialog(){
    if(!$this->user->check_access('numbers/create_outage'))
      throw new RuntimeException();
    $workgroups = $this->em->getRepository('domain\workgroup')->findBy([], ['name' => 'ASC']);
    $streets = $this->em->getRepository('domain\street')->findBy([], ['name' => 'ASC']);
    $groups = $this->em->getRepository('domain\group')->findBy([], ['name' => 'ASC']);
    return $this->twig->render('outage\edit_dialog.tpl', [
                                                          'workgroups' => $workgroups,
                                                          'streets' => $streets,
                                                          'groups' => $groups,
                                                          'outage' => $this->outage
                                                          ]);
  }

  public function update($begin, $target, $type, $houses = [], $performers = [], $description){
    if(!$this->user->check_access('numbers/create_outage'))
      throw new RuntimeException();
    $houses = $this->em->getRepository('domain\house')->findById($houses);
    $performers = $this->em->getRepository('domain\user')->findById($performers);
    $workgroup = $this->em->getRepository('domain\workgroup')->findOneById($type);
    $this->outage->update($begin, $target, $workgroup, $this->user, $houses, $performers, $description);
    $this->em->flush();
    return [
              'outage' => $this->twig->render('outage\outage.tpl',
                                              [
                                                'outage' => $this->outage,
                                                'user' => $this->user
                                              ])
            ];
  }
}