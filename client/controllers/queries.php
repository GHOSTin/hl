<?php namespace client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\queries as controller;

class queries{

  public function default_page(Application $app){
    return $app['client\models\queries']->default_page();
  }

  public function request(Application $app){
    return $app['client\models\queries']->request();
  }

  public function send_request(Application $app, Request $request){
    preg_match_all(controller::RE_DESCRIPTION, $request->get('description'), $matches);
    return $app['client\models\queries']->send_request(implode('', $matches[0]));
  }
}