<?php namespace tests\main\controllers;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use main\controllers\registrations as controller;

class registrations_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->app = new Application();
    $this->controller = new controller();
    $this->model = $this->getMockBuilder('main\models\registrations')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app['main\models\registrations'] = $this->model;
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_open(){
    $this->model->expects($this->once())
                ->method('get_open_requests')
                ->willReturn(['render_template']);
    $response = $this->controller->open($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }
}