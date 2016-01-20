<?php namespace tests\main\controllers;

use Silex\Application;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\groups as controller;
use domain\user;

class groups_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('Twig_Environment')
                 ->disableOriginalConstructor()
                 ->getMock();
    $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()
               ->getMock();
    $this->model = $this->getMockBuilder('main\models\groups')
                  ->disableOriginalConstructor()
                  ->getMock();
    $this->request = new Request();
    $this->app = new Application();
    $this->controller = new controller();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
    $this->app['main\models\groups'] = $this->model;
  }

  public function test_create_group(){
    $this->request->query->set('name', 'Сантехники');
    $this->model->expects($this->once())
                ->method('get_groups')
                ->willReturn('render_template');
    $this->model->expects($this->once())
                ->method('create_group')
                ->with('Сантехники')
                ->willReturn('render_template');
    $response = $this->controller->create_group($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_create_group(){
    $this->model->expects($this->once())
                ->method('get_dialog_create_group')
                ->willReturn('render_template');
    $response = $this->controller->get_dialog_create_group($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_groups(){
    $this->model->expects($this->once())
                ->method('get_groups')
                ->willReturn('render_template');
    $response = $this->controller->get_groups($this->app);
    $this->assertEquals('render_template', $response);
  }
}