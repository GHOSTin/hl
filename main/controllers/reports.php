<?php namespace main\controllers;

use Silex\Application;

class reports{

  public function default_page(Application $app){
    return $app['main\models\reports']->default_page();
  }
}