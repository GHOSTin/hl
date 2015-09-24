<?php namespace main\controllers;

use Silex\Application;

class report_user_access{

  public function report(Application $app){
    return $app['main\models\report_user_access']->report();
  }
}