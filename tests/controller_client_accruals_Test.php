<?php

use Silex\Application;
use client\controllers\accruals as controller;

class controller_client_accruals_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
  }

  public function test_default_page(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('accruals/default_page.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}