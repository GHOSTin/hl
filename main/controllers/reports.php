<?php namespace main\controllers;

use Silex\Application;

class reports{

  public function default_page(Application $app){
    $model = $app['main\models\factory']->get_reports_model();
    return $model->default_page();
  }
}