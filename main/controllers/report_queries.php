<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class report_queries{

  public function clear_filters(Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->clear_filters();
  }

  public function default_page(Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->default_page();
  }

  public function report1(Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->report1();
  }

  public function report1_xls(Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->report1_xls();
  }

  public function set_department(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_department($request->get('id'));
  }

  public function set_house(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_house($request->get('id'));
  }

  public function set_query_type(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_query_type($request->get('id'));
  }

  public function set_time_begin(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_time_begin($request->get('time'));
  }

  public function set_time_end(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_time_end($request->get('time'));
  }

  public function set_status(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_status($request->get('status'));
  }

  public function set_street(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_street($request->get('id'));
  }

  public function set_worktype(Request $request, Application $app){
    $model = $app['main\models\factory']->get_report_queries_model();
    return $model->set_worktype($request->get('id'));
  }
}