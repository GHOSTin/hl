<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class events{

  public function default_page(Application $app){
    $response = $app['main\models\events']->default_page();
    return $app->json($response);
  }

  public function get_day_events(Application $app, $date){
    $response = $app['main\models\events']->get_day_events($date);
    return $app->json($response);
  }

  public function get_dialog_create_event(Application $app){
    return $app['main\models\events']->get_dialog_create_event();
  }

  public function houses(Application $app, $id){
    return $app['main\models\events']->houses($id);
  }

  public function numbers(Application $app, $id){
    return $app['main\models\events']->numbers($id);
  }

  public function create_event(Request $request, Application $app){
    return $app['main\models\events']->create_event(
                                                    $request->get('number'),
                                                    $request->get('event'),
                                                    $request->get('date'),
                                                    $request->get('comment'),
                                                    $request->get('files')
                                                    );
  }
}