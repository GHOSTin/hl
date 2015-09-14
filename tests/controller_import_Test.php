<?php

use Silex\Application;
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

  public function test_load_flats(){
    $model = $this->getMockBuilder('main\models\import_numbers')
                 ->disableOriginalConstructor()
                 ->getMock();
    $model->expects($this->once())
          ->method('load_flats');
    $model->expects($this->once())
          ->method('get_numbers')
          ->willReturn('numbers_array');
    $this->app['main\models\import_numbers'] = $model;
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('import\load_flats.tpl',
                             [
                              'user' => 'user_object',
                              'numbers' => 'numbers_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->load_flats($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_load_houses(){
    $model = $this->getMockBuilder('main\models\import_numbers')
                 ->disableOriginalConstructor()
                 ->getMock();
    $model->expects($this->once())
          ->method('load_houses');
    $model->expects($this->once())
          ->method('get_flats')
          ->willReturn('flats_array');
    $this->app['main\models\import_numbers'] = $model;
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('import\load_houses.tpl',
                             [
                              'user' => 'user_object',
                              'flats' => 'flats_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->load_houses($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_load_numbers(){
    $model = $this->getMockBuilder('main\models\import_numbers')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('load_numbers');
    $this->app['main\models\import_numbers'] = $model;
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('import\load_numbers.tpl', ['user' => 'user_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->load_numbers($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_load_streets(){
    $model = $this->getMockBuilder('main\models\import_numbers')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('load_streets');
    $model->expects($this->once())
          ->method('get_houses')
          ->willReturn('houses_array');
    $this->app['main\models\import_numbers'] = $model;
    $this->app['user'] = 'user_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('import\load_streets.tpl',
                             [
                              'user' => 'user_object',
                              'houses' => 'houses_array'
                             ])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->load_streets($this->app);
    $this->assertEquals('render_template', $response);
  }
}