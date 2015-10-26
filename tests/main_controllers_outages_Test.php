<?php

use Silex\Application;
use main\controllers\outages as controller;
use Symfony\Component\HttpFoundation\Request;

class main_controllers_outages_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\outages')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\outages'] = $this->model;
  }

  public function test_create(){
    $this->request->request->set('begin', '12:00 21.12.1984');
    $this->request->request->set('target', '12:00 21.12.1984');
    $this->request->request->set('type', 126);
    $this->request->request->set('houses', [1, 6]);
    $this->request->request->set('performers', [2, 3]);
    $this->request->request->set('description', 'привет');
    $this->model->expects($this->once())
                ->method('create')
                ->with(
                        472460400,
                        472460400,
                        126,
                        [1, 6],
                        [2, 3],
                        'привет'
                      );
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn(['render_template']);
    $response = $this->controller->create($this->app, $this->request);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_default_page(){
    $this->model->expects($this->once())
                ->method('default_page')
                ->willReturn(['render_template']);
    $response = $this->controller->default_page($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_dialog_create(){
    $this->model->expects($this->once())
                ->method('dialog_create')
                ->willReturn('render_template');
    $response = $this->controller->dialog_create($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_edit(){
    $model = $this->getMockBuilder('main\models\outage')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('get_edit_dialog')
          ->willReturn('render_template');
    $factory = $this->getMockBuilder('main\models\factory')
                    ->disableOriginalConstructor()
                    ->getMock();
    $factory->expects($this->once())
            ->method('get_outage_model')
            ->with('125')
            ->willReturn($model);
    $this->app['main\models\factory'] = $factory;
    $response = $this->controller->edit($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_update(){
    $this->request->request->set('begin', '12:00 21.12.1984');
    $this->request->request->set('target', '12:00 21.12.1984');
    $this->request->request->set('type', 126);
    $this->request->request->set('houses', [1, 6]);
    $this->request->request->set('performers', [2, 3]);
    $this->request->request->set('description', 'привет');
    $model = $this->getMockBuilder('main\models\outage')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('update')
          ->with(
                  472460400,
                  472460400,
                  126,
                  [1, 6],
                  [2, 3],
                  'привет'
                )
          ->willReturn(['render_template']);
    $factory = $this->getMockBuilder('main\models\factory')
                    ->disableOriginalConstructor()
                    ->getMock();
    $factory->expects($this->once())
            ->method('get_outage_model')
            ->with('125')
            ->willReturn($model);
    $this->app['main\models\factory'] = $factory;
    $response = $this->controller->update($this->app, $this->request, 125);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_houses(){
    $this->model->expects($this->once())
                ->method('houses')
                ->with(125)
                ->willReturn('render_template');
    $response = $this->controller->houses($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_active(){
    $this->model->expects($this->once())
                ->method('active')
                ->willReturn(['render_template']);
    $response = $this->controller->active($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_today(){
    $this->model->expects($this->once())
                ->method('today')
                ->willReturn(['render_template']);
    $response = $this->controller->today($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_yesterday(){
    $this->model->expects($this->once())
                ->method('yesterday')
                ->willReturn(['render_template']);
    $response = $this->controller->yesterday($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_week(){
    $this->model->expects($this->once())
                ->method('week')
                ->willReturn(['render_template']);
    $response = $this->controller->week($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_lastweek(){
    $this->model->expects($this->once())
                ->method('lastweek')
                ->willReturn(['render_template']);
    $response = $this->controller->lastweek($this->app);
    $this->assertEquals($this->app->json(['render_template']), $response);
  }

  public function test_users(){
    $this->model->expects($this->once())
                ->method('users')
                ->with(125)
                ->willReturn('render_template');
    $response = $this->controller->users($this->app, 125);
    $this->assertEquals('render_template', $response);
  }
}