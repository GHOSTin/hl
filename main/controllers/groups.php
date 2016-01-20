<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class groups{

  public function create_group(Request $request, Application $app){
    $app['main\models\groups']->create_group($request->get('name'));
    return $app['main\models\groups']->get_groups();
  }

  public function get_groups(Application $app){
    return $app['main\models\groups']->get_groups();
  }

  public function get_dialog_create_group(Application $app){
    return $app['main\models\groups']->get_dialog_create_group();
  }
}