<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\import as controller;

class controller_import_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
  }

  public function test_default_page(){
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('import\default_page.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}