<?php namespace tests\client\controllers;

use Silex\Application;
use client\controllers\arrears as controller;
use PHPUnit_Framework_TestCase;

class arrears_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('client\models\arrears')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['client\models\arrears'] = $this->model;
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page');
    $this->controller->default_page($this->app);
  }

  public function test_flat(){
    $this->model->expects($this->once())
                ->method('flat')
                ->with(125);
    $this->controller->flat($this->app, 125);
  }

  public function test_flats(){
    $this->model->expects($this->once())
                ->method('flats')
                ->with(125);
    $this->controller->flats($this->app, 125);
  }

  public function test_houses(){
    $this->model->expects($this->once())
                ->method('houses')
                ->with(125);
    $this->controller->houses($this->app, 125);
  }

  public function test_top(){
    $this->app['debt_limit'] = 50000;
    $this->model->expects($this->once())
                ->method('top')
                ->with(125, 50000);
    $this->controller->top($this->app, 125);
  }
}