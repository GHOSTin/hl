<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use client\controllers\settings as controller;
use domain\number;

class controller_client_settings_Test extends PHPUnit_Framework_TestCase{

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

  public function test_cellphone_form(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/cellphone/form.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->cellphone_form($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/default_page.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_email_form(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/email/form.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->email_form($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_change_cellphone(){
    $this->request->request->set('cellphone', '+7(922)294-47-42');
    $number = $this->getMock('domain\number');
    $number->expects($this->once())
           ->method('set_cellphone')
           ->with('9222944742');
    $this->app['number'] = $number;
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/cellphone/success.tpl', ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->change_cellphone($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_change_email(){
    $this->request->request->set('email', 'nekrasov@mlsco.ru');
    $number = $this->getMock('domain\number');
    $number->expects($this->once())
           ->method('set_email')
           ->with('nekrasov@mlsco.ru');
    $this->app['number'] = $number;
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/email/success.tpl', ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->change_email($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_notification_form(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/notification/form.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->notification_form($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_password_form(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/password/form.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->password_form($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_change_notification(){
    $this->request->request->set('email', 'on');
    $this->request->request->set('cellphone', 'on');
    $number = $this->getMock('domain\number');
    $number->expects($this->once())
           ->method('set_email_notification_rule')
           ->with('on');
    $number->expects($this->once())
           ->method('set_cellphone_notification_rule')
           ->with('on');
    $this->app['number'] = $number;
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/notification/success.tpl', ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->change_notification($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_change_password_1(){
    $this->request->request->set('new_password', 'Aa123456');
    $this->request->request->set('confirm_password', 'Aa654321');
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/password/not_identical.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->change_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_change_password_2(){
    $this->request->request->set('old_password', 'Aa12345678');
    $this->request->request->set('new_password', 'Aa123456');
    $this->request->request->set('confirm_password', 'Aa123456');
    $number = $this->getMock('domain\number');
    $number->expects($this->once())
           ->method('get_hash')
           ->will($this->returnValue('bad_hash'));
    $this->app['number'] = $number;
    $this->app['salt'] = 'salt';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/password/wrong_old_password.tpl', ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->change_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_change_password_3(){
    $this->request->request->set('old_password', 'Aa12345678');
    $this->request->request->set('new_password', 'Aa123456');
    $this->request->request->set('confirm_password', 'Aa123456');
    $number = $this->getMock('domain\number');
    $old_hash = number::generate_hash('Aa12345678', 'salt');
    $new_hash = number::generate_hash('Aa123456', 'salt');
    $number->expects($this->once())
           ->method('get_hash')
           ->will($this->returnValue($old_hash));
    $number->expects($this->once())
           ->method('set_hash')
           ->with($new_hash);
    $this->app['number'] = $number;
    $this->app['salt'] = 'salt';
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('settings/password/success.tpl', ['number' => $number])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->change_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}