<?php namespace tests\main\controllers;

use Silex\Application;
use main\controllers\report_user_access as controller;
use PHPUnit_Framework_TestCase;

class report_user_access_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\report_user_access')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['main\models\report_user_access'] = $this->model;
  }

  public function test_report(){
    $this->model->expects($this->once())
                ->method('report')
                ->willReturn('render_template');
    $response = $this->controller->report($this->app);
    $this->assertEquals('render_template', $response);
  }
}