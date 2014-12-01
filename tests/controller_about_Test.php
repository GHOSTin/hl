<?php

use \Silex\Application;
use \main\controllers\default_page;
use \domain\user;

class controller_about_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $this->app = new Application();
    $this->controller = new default_page();
    $this->app['twig'] = $twig;
  }

  public function test_about(){
    $user = new user();
    $this->app['user'] = $user;
    $this->app['twig']->expects($this->once())
        ->method('render')
        ->with('about.tpl', ['user' => $this->app['user']])
        ->will($this->returnValue('render_template'));
    $response = $this->controller->about($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page_1(){
    $user = new user();
    $this->app['user'] = $user;
    $this->app['twig']->expects($this->once())
        ->method('render')
        ->with('default_page.tpl', ['user' => $this->app['user']])
        ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page_2(){
    $this->app['user'] = null;
    $this->app['twig']->expects($this->once())
        ->method('render')
        ->with('enter.tpl', ['user' => $this->app['user']])
        ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}