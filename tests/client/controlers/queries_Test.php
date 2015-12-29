<?php namespace tests\client\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use client\controllers\queries as controller;
use PHPUnit_Framework_TestCase;

class queries_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('client\models\queries')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['client\models\queries'] = $this->model;
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page');
    $this->controller->default_page($this->app);
  }

  public function test_request(){
    $this->model->expects($this->once())
                ->method('request');
    $this->controller->request($this->app);
  }

  public function test_send_request(){
    $this->request->request->set('description', 'Описание заявки');
    $this->model->expects($this->once())
                ->method('send_request')
                ->with('Описание заявки');
    $this->controller->send_request($this->app, $this->request);
  }
}