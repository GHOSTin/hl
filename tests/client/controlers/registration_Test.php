<?php namespace tests\client\controllers;

use Silex\Application;
use client\controllers\registration as controller;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit_Framework_TestCase;

class registration_Test extends PHPUnit_Framework_TestCase{

  const email = 'target@example.com';
  const number = '038464888';
  const address = 'Ватутина 52 кв 19';
  const fio = 'Некрасов Евгений Валерьевич';
  const telephone = '65-78-96';
  const cellphone = '+792229444742';

  public function setUp(){
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->model = $this->getMockBuilder('client\models\registration')
                        ->disableOriginalConstructor()
                        ->getMock();
    $this->app['client\models\registration'] = $this->model;
  }

  public function test_registration_form(){
    $this->model->expects($this->once())
                ->method('registration_form')
                ->willReturn('render_template');
    $response = $this->controller->registration_form($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_process_registration_form(){
    $this->request->request->set('email', ' '.self::email.' ');
    $this->request->request->set('number', ' '.self::number.' ');
    $this->request->request->set('address', ' '.self::address.' ');
    $this->request->request->set('fio', ' '.self::fio.' ');
    $this->request->request->set('telephone', ' '.self::telephone.' ');
    $this->request->request->set('cellphone', ' '.self::cellphone.' ');
    $this->model->expects($this->once())
                ->method('create_request')
                ->with(self::number, self::fio, self::address, self::email, self::telephone, self::cellphone)
                ->willReturn('render_template');
    $response = $this->controller->process_registration_form($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}