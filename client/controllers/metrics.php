<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class metrics{

  public function default_page(Application $app){
    return $app['twig']->render('metrics/default_page.tpl', ['number' => $app['number']]);
  }

  public function send(Request $request, Application $app){
    $message[] = 'ГВС-1: '.$request->get('gvs1');
    $message[] = 'ГВС-2: '.$request->get('gvs2');
    $message[] = 'ХВС-1: '.$request->get('hvs1');
    $message[] = 'ХВС-2: '.$request->get('hvs2');
    $message[] = 'Электроэнергия-1: '.$request->get('electrical1');
    $message[] = 'Электроэнергия-2: '.$request->get('electrical2');
    $metrics = $app['domain\metrics'];
    $metrics->set_id(sha1(time().rand(1, 1000)));
    $metrics->set_time(time());
    $metrics->set_address($request->get('address'));
    $metrics->set_metrics(implode(PHP_EOL, $message));
    $metrics->set_status('actual');
    $app['em']->persist($metrics);
    $app['em']->flush();
    return $app['twig']->render('metrics/send.tpl', ['number' => $app['number']]);
  }
}