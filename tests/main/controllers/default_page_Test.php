<?php namespace tests\main\controllers;

use Silex\Application;
use main\controllers\default_page as controller;
use PHPUnit_Framework_TestCase;

class default_page_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
  }

  public function test_about(){
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('about.tpl', ['user' => 'user_object'])
                      ->willReturn('render_template');
    $response = $this->controller->about($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page_1(){
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('default_page.tpl', ['user' => 'user_object'])
                      ->willReturn('render_template');
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page_2(){
    $this->app['user'] = null;
    $response = $this->controller->default_page($this->app);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
    $this->assertEquals('/enter/', $response->getTargetUrl());
  }
}