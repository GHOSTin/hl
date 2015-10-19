<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class outages{

  public function create(Application $app, Request $request){
    $app['main\models\outages']->create(
                                        strtotime('12:00 '. $request->get('begin')),
                                        strtotime('12:00 '. $request->get('target')),
                                        $request->get('type'),
                                        $request->get('houses'),
                                        $request->get('performers'),
                                        $request->get('description')
                                        );
    $response = $app['main\models\outages']->default_page();
    return $app->json($response);
  }

  public function default_page(Application $app){
    $response = $app['main\models\outages']->default_page();
    return $app->json($response);
  }

  public function dialog_create(Application $app){
    return $app['main\models\outages']->dialog_create();
  }

  public function houses(Application $app, $id){
    return $app['main\models\outages']->houses($id);
  }

  public function users(Application $app, $id){
    return $app['main\models\outages']->users($id);
  }
}