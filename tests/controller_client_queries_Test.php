<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use client\controllers\queries as controller;

class controller_client_queries_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
  }

  public function test_default_page(){
    $collection = $this->getMock('Doctrine\Common\Collections\ArrayCollection');
    $collection->expects($this->once())
               ->method('filter')
               ->will($this->returnValue('query_array'));
    $number = $this->getMock('domain\number');
    $number->expects($this->once())
           ->method('get_queries')
           ->will($this->returnValue($collection));
    $this->app['number'] = $number;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('queries/default_page.tpl', ['number' => $number, 'queries' => 'query_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}