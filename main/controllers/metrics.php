<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class metrics{

  public function archive(Application $app){
    return $app['twig']->render('\metrics\archive.tpl',
                                ['user' => $app['user']]);
  }

  public function default_page(Application $app){
    $metrics = $app['em']->getRepository('\domain\metrics')
                         ->findByStatus('actual');
    return $app['twig']->render('\metrics\default_page.tpl',
                                ['user' => $app['user'], 'metrics' => $metrics]);
  }

  public function remove_metrics(Request $request, Application $app){
    $ids = $request->get('metric');
    if(!empty($ids))
      foreach($ids as $id){
        $metric = $app['em']->find('\domain\metrics', $id);
        if(!is_null($metric))
          $metric->set_status('archive');
      }
    $app['em']->flush();
    return $app['twig']->render('\metrics\remove_metrics.tpl', ['ids' => $ids]);
  }

  public function set_date(Request $request, Application $app){
    $metrics = $app['em']->getRepository('\domain\metrics')
                         ->findByStatusBetween('archive',
                                               $request->get('time'));
    return $app['twig']->render('\metrics\set_date.tpl',
                                ['metrics' => $metrics]);
  }
}