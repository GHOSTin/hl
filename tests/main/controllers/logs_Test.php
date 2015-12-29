<?php namespace tests\main\controllers;

use Silex\Application;
use main\controllers\logs as controller;
use PHPUnit_Framework_TestCase;

class logs_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\logs')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['main\models\logs'] = $this->model;
  }

  public function test_client(){
    $this->model->expects($this->once())
                ->method('client')
                ->willReturn('render_template');
    $response = $this->controller->client($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_main(){
    $this->model->expects($this->once())
                ->method('main')
                ->willReturn('render_template');
    $response = $this->controller->main($this->app);
    $this->assertEquals('render_template', $response);
  }
}