<?php namespace main\controllers;

use Silex\Application;

class logs{

  public function client(Application $app){
    return $app['main\models\logs']->client();
  }

  public function default_page(Application $app){
    return $app['main\models\logs']->default_page();
  }
}