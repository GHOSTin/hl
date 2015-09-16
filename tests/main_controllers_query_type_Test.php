<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\query_type as controller;

class main_controllers_query_type_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\query_type')
                        ->disableOriginalConstructor()
                        ->getMock();
    $factory = $this->getMockBuilder('main\models\factory')
                    ->disableOriginalConstructor()
                    ->getMock();
    $factory->expects($this->once())
            ->method('get_query_type_model')
            ->willReturn($this->model);
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\factory'] = $factory;
  }

  public function test_color(){
    $this->request->request->set('color', 'cccccc');
    $this->model->expects($this->once())
                ->method('update_color')
                ->with('cccccc');
    $this->controller->color($this->app, $this->request, 125);
  }
}