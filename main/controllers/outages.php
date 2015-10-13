<?php namespace main\controllers;

use Silex\Application;

class outages{

  public function default_page(Application $app){
    $response = $app['main\models\outages']->default_page();
    return $app->json($response);
  }
}