<?php namespace main\controllers;

use Silex\Application;

class notification_center{

  public function get_content(Application $app){
    return $app['main\models\notification_center']->get_content();
  }
}