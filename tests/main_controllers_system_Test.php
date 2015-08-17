<?php

use Silex\Application;
use main\controllers\system as controller;

class main_controllers_system_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\system')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['main\models\system'] = $this->model;
  }

  public function test_config(){
    $this->model->expects($this->once())
                ->method('config')
                ->willReturn('render_template');
    $response = $this->controller->config($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}