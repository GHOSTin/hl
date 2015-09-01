<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use client\controllers\recovery as controller;

class client_controllers_recovery_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
  }

  public function test_recovery_form(){
    $model = $this->getMockBuilder('client\models\recovery')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('recovery_form')
          ->willReturn('render_template');
    $this->app['client\models\recovery'] = $model;
    $response = $this->controller->recovery_form($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_recovery_password(){
    $this->request->request->set('number', ' 125 ');
    $this->request->server->set('REMOTE_ADDR', '127.0.0.1');
    $this->request->server->set('HTTP_USER_AGENT', 'firefox');
    $this->request->headers->set('X-Forwarded-For', '8.8.8.8');
    $model = $this->getMockBuilder('client\models\recovery')
                  ->disableOriginalConstructor()
                  ->getMock();
    $model->expects($this->once())
          ->method('recovery')
          ->with(
                  '125',
                  'salt',
                  'Swift_Message',
                  'mailer',
                  'email_for_reply',
                  [
                    'login' => ' 125 ',
                    'ip' => '127.0.0.1',
                    'xff' => '8.8.8.8',
                    'agent' => 'firefox'
                  ]
                )
          ->willReturn('render_template');
    $this->app['client\models\recovery'] = $model;
    $this->app['salt'] = 'salt';
    $this->app['Swift_Message'] = 'Swift_Message';
    $this->app['mailer'] = 'mailer';
    $this->app['email_for_reply'] = 'email_for_reply';
    $response = $this->controller->recovery_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}