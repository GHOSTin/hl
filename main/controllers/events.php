<?php namespace main\controllers;

use Silex\Application;

class events{

  public function default_page(Application $app){
    $response = $app['main\models\events']->default_page();
    return $app->json($response);
  }

  public function get_day_events(Application $app, $date){
    $response = $app['main\models\events']->get_day_events($date);
    return $app->json($response);
  }
}