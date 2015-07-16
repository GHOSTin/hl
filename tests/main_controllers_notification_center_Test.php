<?php

use Silex\Application;
use main\controllers\notification_center as controller;

class main_conrollers_notification_center_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\notification_center')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['main\models\notification_center'] = $this->model;
  }

  public function test_get_content(){
    $this->model->expects($this->once())
                ->method('get_content');
    $this->controller->get_content($this->app);
  }
}