<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\api_keys as controller;

class main_controllers_api_key_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\api_keys')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['main\models\api_keys'] = $this->model;
  }

  public function test_create(){
    $this->request->query->set('name', 'Даниловское');
    $this->model->expects($this->once())
                ->method('create')
                ->with('Даниловское')
                ->willReturn('render_template');
    $response = $this->controller->create($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_create_dialog(){
    $this->model->expects($this->once())
                ->method('create_dialog')
                ->willReturn('render_template');
    $response = $this->controller->create_dialog($this->app);
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