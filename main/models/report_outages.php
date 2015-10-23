<?php namespace main\models;

use RuntimeException;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use domain\user;

class report_outages{

  private $em;
  private $twig;

  public function __construct(EntityManager $em, Twig_Environment $twig, user $user){
    $this->em = $em;
    $this->twig = $twig;
    $this->user = $user;
    if(!$user->check_access('system/general_access'))
      throw new RuntimeException();
  }

  public function default_page(){
    return $this->twig->render('report_outages\default_page.tpl');
  }

  public function html(){
    $outages = $this->em->getRepository('domain\outage')
                        ->findByBeginTimeRange('00:00 21.12.1984', '23:59 21.12.2015');
    return $this->twig->render('report_outages\html.tpl', [
                                                            'outages' => $outages,
                                                            'user' => $this->user
                                                          ]);
  }
}