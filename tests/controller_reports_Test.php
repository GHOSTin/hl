<?php

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \main\controllers\reports as controller;

class controller_reports_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()
               ->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_clear_filter_query(){
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_filters')
          ->will($this->returnValue('filters_array'));
    $model->expects($this->once())
          ->method('init_default_params');
    $this->app['main\models\report_query'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('report\clear_filter_query.tpl', ['filters' => 'filters_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->clear_filter_query($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('report\default_page.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_filter_query_department(){
    $this->request->query->set('id', 125);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_department')
          ->with(125);
    $this->app['main\models\report_query'] = $model;
    $response = $this->controller->set_filter_query_department($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_filter_query_house(){
    $this->request->query->set('id', 125);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_house')
          ->with(125);
    $this->app['main\models\report_query'] = $model;
    $response = $this->controller->set_filter_query_house($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_filter_query_status(){
    $this->request->query->set('status', 'open');
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_status')
          ->with('open');
    $this->app['main\models\report_query'] = $model;
    $response = $this->controller->set_filter_query_status($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_filter_query_street(){
    $this->request->query->set('id', 125);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_street')
          ->with(125);
    $this->app['main\models\report_query'] = $model;
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\street', 125)
                    ->will($this->returnValue('street_object'));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('report\set_filter_query_street.tpl', ['street' => 'street_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->set_filter_query_street($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_filter_query_worktype(){
    $this->request->query->set('id', 125);
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_worktype')
          ->with(125);
    $this->app['main\models\report_query'] = $model;
    $response = $this->controller->set_filter_query_worktype($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_time_begin(){
    $this->request->query->set('time', '21.12.1984');
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_time_begin')
          ->with('472417200');
    $this->app['main\models\report_query'] = $model;
    $response = $this->controller->set_time_begin($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_time_end(){
    $this->request->query->set('time', '21.12.1984');
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_time_end')
          ->with('472503559');
    $this->app['main\models\report_query'] = $model;
    $response = $this->controller->set_time_end($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_report_query_one(){
    $model = $this->getMockBuilder('main\models\report_query')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_queries')
          ->will($this->returnValue('query_array'));
    $this->app['main\models\report_query'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('report\report_query_one.tpl', ['queries' => 'query_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->report_query_one($this->app);
    $this->assertEquals('render_template', $response);
  }
}