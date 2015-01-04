<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use client\controllers\default_page as controller;

class controller_client_default_page_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $twig = $this->getMockBuilder('\Twig_Environment')
                 ->disableOriginalConstructor()->getMock();
    $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
               ->disableOriginalConstructor()->getMock();
    $this->app = new Application();
    $this->controller = new controller();
    $this->request = new Request();
    $this->app['twig'] = $twig;
    $this->app['em'] = $em;
  }

  public function test_default_page_1(){
    $this->app['number'] = null;
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('enter.tpl', ['number' => null])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_default_page_2(){
    $this->app['number'] = 'number_object';
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('default_page.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->default_page($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_recovery(){
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('recovery/default_page.tpl')
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->recovery($this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_recovery_password_1(){
    $this->request->request->set('number', '037546');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->will($this->returnValue(null));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\number')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('recovery/not_found_number.tpl', ['number' => '037546'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->recovery_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_recovery_password_2(){
    $this->request->request->set('number', '037546');
    $number = $this->getMock('domain\number');
    $number->expects($this->once())
           ->method('get_email')
           ->will($this->returnValue(null));
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->will($this->returnValue($number));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\number')
                    ->will($this->returnValue($repository));
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('recovery/email_not_exists.tpl', ['number' => '037546'])
                      ->will($this->returnValue('render_template'));
    $response = $this->controller->recovery_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }

  public function test_recovery_password_3(){
    $this->request->request->set('number', '037546');
    $number = $this->getMock('domain\number');
    $number->expects($this->once())
           ->method('get_email')
           ->will($this->returnValue('mail@example.com'));
     $number->expects($this->once())
           ->method('set_hash');
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->will($this->returnValue($number));
    $this->app['em']->expects($this->once())
                    ->method('getRepository')
                    ->with('domain\number')
                    ->will($this->returnValue($repository));
    $this->app['em']->expects($this->once())
                    ->method('flush');
    $this->app['twig']->expects($this->once())
                      ->method('render')
                      ->with('recovery/success.tpl', ['number' => 'number_object'])
                      ->will($this->returnValue('render_template'));
    $mailer = $this->getMockBuilder('Swift_Mailer')
                   ->disableOriginalConstructor()
                   ->getMock();

    $message = $this->getMockBuilder('Swift_Message')
                    ->disableOriginalConstructor()
                    ->getMock();
    $message->expects($this->once())
            ->method('setSubject')
            ->with('Востановление пароля')
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setFrom')
            ->with(['rcmp@mlsco.ru'])
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setTo')
            ->with(['mail@example.com'])
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setBody');
    $mailer->expects($this->once())
           ->method('send')
           ->with($this->identicalTo($message));
    $this->app['mailer']        = $mailer;
    $this->app['Swift_Message'] = $message;
    $this->app['salt']          = 'salt';
    $this->app['number']        = 'number_object';
    $response = $this->controller->recovery_password($this->request, $this->app);
    $this->assertEquals('render_template', $response);
  }
}