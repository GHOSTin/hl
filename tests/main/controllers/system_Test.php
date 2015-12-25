<?php namespace tests\main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\system as controller;
use PHPUnit_Framework_TestCase;

class system_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\system')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->search = $this->getMockBuilder('main\models\system_search')
                         ->disableOriginalConstructor()
                         ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\system'] = $this->model;
    $this->app['main\models\system_search'] = $this->search;
  }

  public function test_config(){
    $reflection = $this->getMockBuilder('ReflectionClass')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->model->expects($this->once())
                ->method('config')
                ->with($reflection)
                ->willReturn('render_template');
    $this->app['main\models\system'] = $this->model;
    $this->app['config_reflection'] = $reflection;
    $response = $this->controller->config($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_search_number(){
    $this->request->request->set('number', 125);
    $this->search->expects($this->once())
                 ->method('search_number')
                 ->with(125)
                 ->willReturn('render_template');
    $response = $this->controller->search_number($this->app, $this->request);
    $this->assertEquals('render_template', $response);
  }

  public function test_search_number_form(){
    $this->search->expects($this->once())
                 ->method('search_number_form')
                 ->willReturn('render_template');
    $response = $this->controller->search_number_form($this->app);
    $this->assertEquals('render_template', $response);
  }
}