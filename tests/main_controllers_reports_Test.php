<?php

use Silex\Application;
use main\controllers\reports as controller;

class main_controllers_reports_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->app = new Application();
    $this->controller = new controller();
    $this->model = $this->getMockBuilder('main\models\reports')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app['main\models\reports'] = $this->model;
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}