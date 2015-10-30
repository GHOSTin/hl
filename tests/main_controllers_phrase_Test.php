<?php

use Silex\Application;
use main\controllers\phrase as controller;
use Symfony\Component\HttpFoundation\Request;

class main_controllers_phrase_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\phrase')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->factory = $this->getMockBuilder('main\models\factory')
                          ->disableOriginalConstructor()
                          ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\factory'] = $this->factory;
    $this->app['main\models\phrase'] = $this->model;
  }

  public function test_remove_dialog(){
    $this->factory->expects($this->once())
                  ->method('get_phrase_model')
                  ->with('125')
                  ->willReturn($this->model);
    $this->model->expects($this->once())
                ->method('remove_phrase_dialog')
                ->willReturn('render_template');
    $response = $this->controller->remove_dialog($this->app, '125');
    $this->assertEquals('render_template', $response);
  }

  public function test_create_phrase(){
    $this->request->request->set('id', '125');
    $this->factory->expects($this->once())
                  ->method('get_phrase_model')
                  ->with('125')
                  ->willReturn($this->model);
    $this->model->expects($this->once())
          ->method('remove')
          ->willReturn('render_template');
    $response = $this->controller->remove($this->app, $this->request, '125');
    $this->assertEquals('render_template', $response);
  }
}