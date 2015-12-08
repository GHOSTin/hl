<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use main\controllers\number as controller;

class controller_number_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->model = $this->getMockBuilder('main\models\number5')
                        ->disableOriginalConstructor()
                        ->getMock();
    $factory = $this->getMockBuilder('main\models\factory')
                    ->disableOriginalConstructor()
                    ->getMock();
    $factory->expects($this->once())
            ->method('get_number_model')
            ->with(125)
            ->willReturn($this->model);
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\factory'] = $factory;
  }


  public function test_add_event(){
    $this->request->request->set('event', 250);
    $this->request->request->set('date', '21.12.1984');
    $this->request->request->set('comment', 'Привет');
    $this->model->expects($this->once())
                ->method('add_event')
                ->with(250, '21.12.1984', 'Привет')
                ->willReturn('render_template');
    $response = $this->controller->add_event($this->request, $this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_generate_password(){
    $message = $this->getMockBuilder('Swift_Message')
                    ->disableOriginalConstructor()
                    ->getMock();
    $mailer = $this->getMockBuilder('Swift_Mailer')
                   ->disableOriginalConstructor()
                   ->getMock();
    $this->app['salt'] = 'salt';
    $this->app['Swift_Message'] = $message;
    $this->app['mailer'] = $mailer;
    $this->app['email_for_reply'] = 'email';
    $this->model->expects($this->once())
                ->method('generate_password')
                ->with('salt', 'email', $message, $mailer)
                ->willReturn('render_template');
    $response = $this->controller->generate_password($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_add_event(){
    $this->model->expects($this->once())
                ->method('get_dialog_add_event')
                ->willReturn('render_template');
    $response = $this->controller->get_dialog_add_event($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_exclude_event(){
    $this->model->expects($this->once())
                ->method('get_dialog_exclude_event')
                ->with(250, 1396332000)
                ->willReturn('render_template');
    $response = $this->controller->get_dialog_exclude_event($this->app, 125, 250, 1396332000);
    $this->assertEquals('render_template', $response);
  }

  public function test_exclude_event(){
    $this->model->expects($this->once())
                ->method('exclude_event')
                ->with(250, 1396332000)
                ->willReturn('render_template');
    $response = $this->controller->exclude_event($this->app, 125, 250, 1396332000);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_contacts(){
    $this->model->expects($this->once())
                ->method('get_dialog_contacts')
                ->willReturn('render_template');
    $response = $this->controller->get_dialog_contacts($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_get_dialog_generate_password(){
    $this->model->expects($this->once())
                ->method('get_dialog_generate_password')
                ->willReturn('render_template');
    $response = $this->controller->get_dialog_generate_password($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_history(){
    $this->model->expects($this->once())
                ->method('history')
                ->willReturn('render_template');
    $response = $this->controller->history($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_meterages(){
    $this->model->expects($this->once())
                ->method('meterages')
                ->willReturn('render_template');
    $response = $this->controller->meterages($this->app, 125);
    $this->assertEquals('render_template', $response);
  }

  public function test_update_contacts(){
    $this->request->request->set('fio', 'Некрасов Евгений Валерьевич');
    $this->request->request->set('telephone', '647957');
    $this->request->request->set('cellphone', '+79222944742');
    $this->request->request->set('email', 'nekrasov@mlsco.ru');
    $this->model->expects($this->once())
                ->method('update_contacts')
                ->with(
                        'Некрасов Евгений Валерьевич',
                        '647957',
                        '+79222944742',
                        'nekrasov@mlsco.ru'
                      )
                ->willReturn('render_template');
    $response = $this->controller->update_contacts($this->app, $this->request, 125);
    $this->assertEquals('render_template', $response);
  }
}