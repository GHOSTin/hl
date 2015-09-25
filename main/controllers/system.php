<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class system{

  public function config(Application $app){
    return $app['main\models\system']->config($app['config_reflection']);
  }

  public function default_page(Application $app){
    return $app['main\models\system']->default_page();
  }

  public function search_number_form(Application $app){
    return $app['main\models\system_search']->search_number_form();
  }

  public function search_number(Application $app, Request $request){
    return $app['main\models\system_search']->search_number($request->request->get('number'));
  }
}