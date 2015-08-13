<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class api_keys{

  public function create(Request $request, Application $app){
    return $app['main\models\api_key']->create($request->get('name'));
  }

  public function create_dialog(Application $app){
    return $app['main\models\api_key']->create_dialog();
  }

  public function default_page(Application $app){
    return $app['main\models\api_key']->default_page();
  }
}