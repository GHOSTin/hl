<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\reports as controller;

class controller_reports_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->model = $this->getMockBuilder('main\models\reports')
                        ->disableOriginalConstructor()
                        ->getMock();
    $factory = $this->getMockBuilder('main\models\factory')
                    ->disableOriginalConstructor()
                    ->getMock();
    $factory->expects($this->once())
            ->method('get_reports_model')
            ->willReturn($this->model);
    $this->app['main\models\factory'] = $factory;
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}