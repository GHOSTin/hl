<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class outages{

  public function active(Application $app){
    $response = $app['main\models\outages']->active();
    return $app->json($response);
  }

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

  public function edit(Application $app, $id){
    $model = $app['main\models\factory']->get_outage_model($id);
    return $model->get_edit_dialog();
  }

  public function update(Application $app, Request $request, $id){
    $model = $app['main\models\factory']->get_outage_model($id);
    $response = $model->update(
                                strtotime('12:00 '. $request->get('begin')),
                                strtotime('12:00 '. $request->get('target')),
                                $request->get('type'),
                                $request->get('houses'),
                                $request->get('performers'),
                                $request->get('description')
                                );
    return $app->json($response);
  }

  public function houses(Application $app, $id){
    return $app['main\models\outages']->houses($id);
  }

  public function today(Application $app){
    $response = $app['main\models\outages']->today();
    return $app->json($response);
  }

  public function yesterday(Application $app){
    $response = $app['main\models\outages']->yesterday();
    return $app->json($response);
  }

  public function week(Application $app){
    $response = $app['main\models\outages']->week();
    return $app->json($response);
  }

  public function lastweek(Application $app){
    $response = $app['main\models\outages']->lastweek();
    return $app->json($response);
  }

  public function users(Application $app, $id){
    return $app['main\models\outages']->users($id);
  }
}