<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\report_queries as controller;

class controller_report_queries_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->model = $this->getMockBuilder('main\models\report_queries')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app['main\models\report_queries'] = $this->model;
  }

  public function test_clear_filters(){
    $this->model->expects($this->once())
                ->method('clear_filters')
                ->willReturn('render_template');
    $response = $this->controller->clear_filters($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_report1(){
    $this->model->expects($this->once())
                ->method('report1')
                ->willReturn('render_template');
    $response = $this->controller->report1($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_report1_xls(){
    $this->model->expects($this->once())
                ->method('report1_xls')
                ->willReturn('render_template');
    $response = $this->controller->report1_xls($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_department(){
    $this->request->query->set('id', 125);
    $this->model->expects($this->once())
                ->method('set_department')
                ->with(125)
                ->willReturn('render_template');
    $response = $this->controller->set_department($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_house(){
    $this->request->query->set('id', 125);
    $this->model->expects($this->once())
                ->method('set_house')
                ->with(125)
                ->willReturn('render_template');
    $response = $this->controller->set_house($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_query_type(){
    $this->request->query->set('id', 125);
    $this->model->expects($this->once())
                ->method('set_query_type')
                ->with(125)
                ->willReturn('render_template');
    $response = $this->controller->set_query_type($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_time_begin(){
    $this->request->query->set('time', 1397562800);
    $this->model->expects($this->once())
                ->method('set_time_begin')
                ->with(1397562800)
                ->willReturn('render_template');
    $response = $this->controller->set_time_begin($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_time_end(){
    $this->request->query->set('time', 1397562800);
    $this->model->expects($this->once())
                ->method('set_time_end')
                ->with(1397562800)
                ->willReturn('render_template');
    $response = $this->controller->set_time_end($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_status(){
    $this->request->query->set('status', 'open');
    $this->model->expects($this->once())
                ->method('set_status')
                ->with('open')
                ->willReturn('render_template');
    $response = $this->controller->set_status($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_street(){
    $this->request->query->set('id', 125);
    $this->model->expects($this->once())
                ->method('set_street')
                ->with(125)
                ->willReturn('render_template');
    $response = $this->controller->set_street($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_set_worktype(){
    $this->request->query->set('id', 125);
    $this->model->expects($this->once())
                ->method('set_worktype')
                ->with(125)
                ->willReturn('render_template');
    $response = $this->controller->set_worktype($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}