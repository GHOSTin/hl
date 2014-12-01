<?php

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \main\controllers\error as controller;
use \domain\error;
use \domain\user;

class controller_error_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_show_dialog(){
    $this->app['twig']->expects($this->once())
        ->method('render')
        ->with('\error\show_dialog.tpl')
        ->will($this->returnValue('render_template'));
    $response = $this->controller->show_dialog($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_delete_error_1(){
    $this->request->query->set('time', 1397562800);
    $this->setExpectedException('RuntimeException');
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\error', 1397562800)
                    ->will($this->returnValue(null));
    $this->controller->delete_error($this->request, $this->app);
  }

  public function test_delete_error_2(){
    $error = new error();
    $this->request->query->set('time', 1397562800);
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\error', 1397562800)
                    ->will($this->returnValue($error));
    $this->app['em']->expects($this->once())
                    ->method('remove')
                    ->with($error);
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $response = $this->controller->delete_error($this->request, $this->app);
    $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response',
                            $response);
  }

  public function test_send_error_1(){
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\error')
                    ->will($this->returnValue(null));
    $this->app['em']->expects($this->once())
                    ->method('persist');
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['user'] = new user();
    $response = $this->controller->send_error($this->request, $this->app);
    $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response',
                            $response);
  }

  public function test_send_error_2(){
    $this->setExpectedException('RuntimeException');
    $error = new error();
    $this->app['em']->expects($this->once())
                    ->method('find')
                    ->with('\domain\error')
                    ->will($this->returnValue($error));
    $this->controller->send_error($this->request, $this->app);
  }

  public function test_default_page(){
    $this->app['user'] = new user();
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('error_array'));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('\error\default_page.tpl',
                             ['user' => $this->app['user'],
                              'errors' => 'error_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }
}