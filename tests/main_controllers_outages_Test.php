<?php

use Silex\Application;
use main\controllers\outages as controller;

class main_controllers_outages_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\outages')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['main\models\outages'] = $this->model;
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn(['render_template']);
    $response = $this->controller->default_page($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }
}