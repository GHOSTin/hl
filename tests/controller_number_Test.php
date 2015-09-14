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
            ->willReturn($this->model);
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['main\models\factory'] = $factory;
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
                ->with('salt', 'email', $message, $mailer);
    $this->controller->generate_password($this->app, 125);
  }

  public function test_get_dialog_contacts(){
    $this->model->expects($this->once())
                ->method('get_dialog_contacts');
    $this->controller->get_dialog_contacts($this->app, 125);
  }

  public function test_get_dialog_generate_password(){
    $this->model->expects($this->once())
                ->method('get_dialog_generate_password');
    $this->controller->get_dialog_generate_password($this->app, 125);
  }

  public function test_history(){
    $this->model->expects($this->once())
                ->method('history');
    $this->controller->history($this->app, 125);
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
                      );
    $this->controller->update_contacts($this->app, $this->request, 125);
  }
}