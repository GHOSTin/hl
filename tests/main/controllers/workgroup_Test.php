<?php namespace tests\main\controllers;

use Silex\Application;
use main\controllers\workgroup as controller;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit_Framework_TestCase;

class workgroup_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\workgroup')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->factory = $this->getMockBuilder('main\models\factory')
                          ->disableOriginalConstructor()
                          ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\factory'] = $this->factory;
    $this->app['main\models\workgroup'] = $this->model;
  }


  public function test_phrases(){
    $model = $this->getMockBuilder('main\models\repository')
                  ->disableOriginalConstructor()
                  ->getMock();
    $workgroup = $this->getMockBuilder('domain\workgroup')
                      ->disableOriginalConstructor()
                      ->getMock();
    $collection = $this->getMockBuilder('Doctrine\Common\Collections\ArrayCollection')
                       ->disableOriginalConstructor()
                       ->getMock();
    $workgroup->expects($this->once())
              ->method('get_phrases')
              ->willReturn($collection);
    $collection->expects($this->once())
              ->method('toArray')
              ->willReturn('phrases_objects');
    $model->expects($this->once())
          ->method('get_workgroup')
          ->with(125)
          ->willReturn($workgroup);
    $this->app['main\models\repository'] = $model;
    $response = $this->controller->phrases($this->app, 125);
    $this->assertEquals($this->app->json(['phrases' => 'phrases_objects']), $response);
  }

  public function test_create_phrase_dialog(){
    $this->factory->expects($this->once())
                  ->method('get_workgroup_model')
                  ->with('125')
                  ->willReturn($this->model);
    $this->model->expects($this->once())
                ->method('create_phrase_dialog')
                ->willReturn('render_template');
    $response = $this->controller->create_phrase_dialog($this->app, '125');
    $this->assertEquals('render_template', $response);
  }

  public function test_create_phrase(){
    $this->request->request->set('phrase', 'Привет');
    $this->factory->expects($this->once())
                  ->method('get_workgroup_model')
                  ->with('125')
                  ->willReturn($this->model);
    $this->model->expects($this->once())
          ->method('create_phrase')
          ->with('Привет')
          ->willReturn(['render_template']);
    $this->model->expects($this->once())
          ->method('get_content')
          ->willReturn('render_template');
    $response = $this->controller->create_phrase($this->app, $this->request, '125');
    $this->assertEquals('render_template', $response);
  }
}