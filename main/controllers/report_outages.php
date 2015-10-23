<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class report_outages{

  public function default_page(Application $app){
    return $app['main\models\report_outages']->default_page();
  }

  public function html(Application $app){
    return $app['main\models\report_outages']->html();
  }

  public function start(Application $app, Request $request){
    return $app['main\models\report_outages']->start($request->get('time'));
  }

  public function end(Application $app, Request $request){
    return $app['main\models\report_outages']->end($request->get('time'));
  }
}