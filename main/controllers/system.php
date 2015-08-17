<?php namespace main\controllers;

use Silex\Application;

class system{

  public function default_page(Application $app){
    return $app['main\models\system']->default_page();
  }

  public function config(Application $app){
    return $app['main\models\system']->config();
  }
}