<?php namespace main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class reports{

  public function clear_filter_query(Application $app){
    $model = $app['main\models\report_query'];
    $model->init_default_params();
    $filters = $model->get_filters();
    return $app['twig']->render('report\clear_filter_query.tpl', ['filters' => $filters]);
  }

  public function default_page(Application $app){
    return $app['twig']->render('report\default_page.tpl', ['user' => $app['user']]);
  }

  public function get_query_reports(Application $app){
    $work_types = $app['em']->getRepository('domain\workgroup')
                            ->findAll(['name' => 'ASC']);
    $departments = $app['em']->getRepository('domain\department')
                             ->findAll(['name' => 'ASC']);
    $streets = $app['em']->getRepository('domain\street')
                         ->findAll(['name' => 'ASC']);
    $query_types = $app['em']->getRepository('domain\query_type')
                             ->findAll(['name' => 'ASC']);
    $model = $app['main\models\report_query'];
    $filters = $model->get_filters();
    $houses = [];
    if(!empty($filters['street']))
      $houses = $app['em']->getRepository('domain\house')
                          ->findByStreet($filters['street']);
    return $app['twig']->render('report\get_query_reports.tpl',
                                [
                                 'filters' => $filters,
                                 'query_work_types' => $work_types,
                                 'query_types' => $query_types,
                                 'departments' => $departments,
                                 'streets' => $streets,
                                 'houses' => $houses
                                ]);
  }

  public function report_query_one(Application $app){
    $model = $app['main\models\report_query'];
    $queries = $model->get_queries();
    return $app['twig']->render('report\report_query_one.tpl', ['queries' => $queries]);
  }

  public function report_query_one_xls(Application $app){
    $model = $app['main\models\report_query'];
    $queries = $model->get_queries();
    $response = new Response();
    $response->setContent($app['twig']->render('report\report_query_one_xls.tpl', ['queries' => $queries]));
    $response->headers->set('Content-Disposition', 'attachment; filename=export.xml');
    $response->headers->set('Content-type', 'application/octet-stream');
    return $response;
  }

  public function set_filter_query_department(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_department($request->get('id'));
    return new Response();
  }

  public function set_filter_query_house(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_house($request->get('id'));
    return new Response();
  }

  public function set_filter_query_status(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_status($request->get('status'));
    return new Response();
  }

  public function set_filter_query_street(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_street($request->get('id'));
    $street = $app['em']->find('\domain\street', $request->get('id'));
    return $app['twig']->render('report\set_filter_query_street.tpl', ['street' => $street]);
  }

  public function set_filter_query_worktype(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_worktype($request->get('id'));
    return new Response();
  }

  public function set_time_begin(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_time_begin(strtotime($request->get('time')));
    return new Response();
  }

  public function set_time_end(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_time_end(strtotime($request->get('time')) + 86359);
    return new Response();
  }

  public function set_query_type(Request $request, Application $app){
    $model = $app['main\models\report_query'];
    $model->set_query_type($request->get('id'));
    return new Response();
  }
}