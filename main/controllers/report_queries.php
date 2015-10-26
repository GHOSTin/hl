<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class report_queries{

  public function clear_filters(Application $app){
    return $app['main\models\report_queries']->clear_filters();
  }

  public function default_page(Application $app){
    return $app['main\models\report_queries']->default_page();
  }

  public function report1(Application $app){
    return $app['main\models\report_queries']->report1();
  }

  public function noclose(Application $app){
    return $app['main\models\report_queries']->noclose();
  }

  public function report1_xls(Application $app){
    return $app['main\models\report_queries']->report1_xls();
  }

  public function set_department(Request $request, Application $app){
    return $app['main\models\report_queries']->set_department($request->get('id'));
  }

  public function set_house(Request $request, Application $app){
    return $app['main\models\report_queries']->set_house($request->get('id'));
  }

  public function set_query_type(Request $request, Application $app){
    return $app['main\models\report_queries']->set_query_type($request->get('id'));
  }

  public function set_time_begin(Request $request, Application $app){
    return $app['main\models\report_queries']->set_time_begin($request->get('time'));
  }

  public function set_time_end(Request $request, Application $app){
    return $app['main\models\report_queries']->set_time_end($request->get('time'));
  }

  public function set_status(Request $request, Application $app){
    return $app['main\models\report_queries']->set_status($request->get('status'));
  }

  public function set_street(Request $request, Application $app){
    return $app['main\models\report_queries']->set_street($request->get('id'));
  }

  public function set_worktype(Request $request, Application $app){
    return $app['main\models\report_queries']->set_worktype($request->get('id'));
  }
}