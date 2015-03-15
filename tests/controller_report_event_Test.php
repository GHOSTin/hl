<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\report_event as controller;

class controller_report_event_Test extends PHPUnit_Framework_TestCase{

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

  public function test_clear(){
    $model = $this->getMockBuilder('main\models\report_event')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_filters')
          ->will($this->returnValue('filters_array'));
    $model->expects($this->once())
          ->method('init_default_params');
    $this->app['main\models\report_event'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('report\clear_filter_event.tpl', ['filters' => 'filters_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->clear($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_event_reports(){
    $model = $this->getMockBuilder('main\models\report_event')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_filters')
          ->will($this->returnValue('filters_array'));
    $this->app['main\models\report_event'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('report\get_event_reports.tpl', ['filters' => 'filters_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_event_reports($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_time_begin(){
    $this->request->query->set('time', '21.12.1984');
    $model = $this->getMockBuilder('main\models\report_event')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_time_begin')
          ->with('472417200');
    $this->app['main\models\report_event'] = $model;
    $response = $this->controller->set_time_begin($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_set_time_end(){
    $this->request->query->set('time', '21.12.1984');
    $model = $this->getMockBuilder('main\models\report_event')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('set_time_end')
          ->with('472503559');
    $this->app['main\models\report_event'] = $model;
    $response = $this->controller->set_time_end($this->request, $this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
  }

  public function test_html(){
    $model = $this->getMockBuilder('main\models\report_event')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_events')
          ->will($this->returnValue('event_array'));
    $this->app['main\models\report_event'] = $model;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('report\report_event_html.tpl', ['events' => 'event_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->html($this->app);
    $this->assertEquals('render_template', $response);
  }
}