<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class report_event{

  public function clear(Application $app){
    $model = $app['main\models\report_event'];
    $model->init_default_params();
    $filters = $model->get_filters();
    return $app['twig']->render('report\clear_filter_event.tpl', ['filters' => $filters]);
  }

  public function get_event_reports(Application $app){
    $model = $app['main\models\report_event'];
    $filters = $model->get_filters();
    return $app['twig']->render('report\get_event_reports.tpl', ['filters' => $filters]);
  }

  public function html(Application $app){
    $model = $app['main\models\report_event'];
    $events = $model->get_events();
    return $app['twig']->render('report\report_event_html.tpl', ['events' => $events]);
  }

  public function set_time_begin(Request $request, Application $app){
    $model = $app['main\models\report_event'];
    $model->set_time_begin(strtotime($request->get('time')));
    return new Response();
  }

  public function set_time_end(Request $request, Application $app){
    $model = $app['main\models\report_event'];
    $model->set_time_end(strtotime($request->get('time')) + 86359);
    return new Response();
  }
}