<?php namespace tests\main\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\query_types as controller;
use domain\query_type;
use PHPUnit_Framework_TestCase;

class query_types_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()
               ->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_private_create_query_type_1(){
    $this->request->query->set('name', 'Привет');
    $this->setExpectedException('RuntimeException');
    $query_type = new query_type();
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue($query_type));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\query_type')
                    ->will($this->returnValue($repository));
    $this->controller->create_query_type($this->request, $this->app);
  }

  public function test_private_create_query_type_2(){
    $this->request->query->set('name', 'Привет');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByName', 'findAll'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByName')
               ->with('Привет')
               ->will($this->returnValue(null));
    $repository->expects($this->once())
               ->method('findAll')
               ->will($this->returnValue('query_types_array'));
    $this->app['em']->expects($this->exactly(2))
                    ->method('getRepository')
                    ->with('domain\query_type')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('persist');
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('system\query_types\query_types.tpl', ['query_types' => 'query_types_array'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->create_query_type($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->getMock();
    $repository->expects($this->once())
               ->method('findAll')
               ->willReturn('query_types_array');
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\query_type')
                    ->will($this->returnValue($repository));
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('system\query_types\default_page.tpl',
                             [
                              'query_types' => 'query_types_array',
                              'user' => 'user_object'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_query_type(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('system\query_types\get_dialog_create_query_type.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->get_dialog_create_query_type($this->app);
    $this->assertEquals('render_template', $response);
  }
}