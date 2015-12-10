<?php namespace main\controllers;

use Silex\Application;

class events{

  public function default_page(Application $app){
    $response = $app['main\models\events']->default_page();
    return $app->json($response);
  }
}