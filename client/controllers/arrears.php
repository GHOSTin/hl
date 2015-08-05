<?php namespace client\controllers;

use Silex\Application;

class arrears{

  public function default_page(Application $app){
    return $app['client\models\arrears']->default_page();
  }

  public function flat(Application $app, $id){
    return $app['client\models\arrears']->flat($id);
  }

  public function flats(Application $app, $id){
    return $app['client\models\arrears']->flats($id);
  }

  public function houses(Application $app, $id){
    return $app['client\models\arrears']->houses($id);
  }

  public function top(Application $app, $id){
    return $app['client\models\arrears']->top($id, $app['debt_limit']);
  }
}