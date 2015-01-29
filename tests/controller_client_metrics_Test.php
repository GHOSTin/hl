<?php

use Silex\Application;
use client\controllers\metrics as controller;
use Symfony\Component\HttpFoundation\Request;

class controller_client_metrics_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()
               ->getMock();
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_default_page(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('metrics/default_page.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_send(){
    $this->request->request->set('address', 'Ватутина');
    $metrics = $this->getMock('domain\metrics');
    $metrics->expects($this->once())
            ->method('set_id');
    $metrics->expects($this->once())
            ->method('set_time');
    $metrics->expects($this->once())
            ->method('set_address')
            ->with('Ватутина');
    $metrics->expects($this->once())
            ->method('set_metrics');
    $metrics->expects($this->once())
            ->method('set_status')
            ->with('actual');
    $this->app['em']->expects($this->once())
                    ->method('persist')
                    ->with($metrics);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['domain\metrics'] = $metrics;
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('metrics/send.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->send($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}