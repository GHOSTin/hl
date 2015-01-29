<?php

use Silex\Application;
use client\controllers\registration as controller;
use Symfony\Component\HttpFoundation\Request;

class controller_client_registration_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig              = $this->getMockBuilder('\Twig_Environment')
                              ->disableOriginalConstructor()
                              ->getMock();
    $this->app         = new Application();
    $this->controller  = new controller();
    $this->request     = new Request();
    $this->app['twig'] = $twig;
  }

  public function test_default_page(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('registration/default_page.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_send(){
    $this->request->request->set('email', 'target@example.com');
    $message = $this->getMockBuilder('Swift_Message')
                    ->disableOriginalConstructor()
                    ->getMock();
    $message->expects($this->once())
            ->method('setSubject')
            ->with('Заявка на доступ')
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setFrom')
            ->with(['target@example.com'])
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setTo')
            ->with(['mail@example.com'])
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setBody');
    $mailer = $this->getMockBuilder('Swift_Mailer')
                   ->disableOriginalConstructor()
                   ->getMock();
    $mailer->expects($this->once())
           ->method('send')
           ->with($this->identicalTo($message));
    $this->app['Swift_Message']          = $message;
    $this->app['mailer']                 = $mailer;
    $this->app['number']                 = 'number_object';
    $this->app['email_for_registration'] = 'mail@example.com';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('registration/send.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->send($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}