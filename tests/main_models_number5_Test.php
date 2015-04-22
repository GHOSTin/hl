<?php

use Silex\Application;
use main\models\number5 as model;

class main_model_number5_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->number = $this->getMock('domain\number');
    $this->app = new Application();
  }

  public function test_construct_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(false);
    new model($this->app, $this->twig, $this->em, $this->user, 125);
  }

  public function test_construct_2(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\number', 125)
             ->willReturn(null);
    new model($this->app, $this->twig, $this->em, $this->user, 125);
  }

  public function test_generate_password_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(
                                  ['numbers/general_access'],
                                  ['numbers/generate_password']
                                )
               ->will($this->onConsecutiveCalls(true, false));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\number', 125)
             ->willReturn($this->number);
    $model = new model($this->app, $this->twig, $this->em, $this->user, 125);
    $model->generate_password();
  }

  public function test_generate_password_2(){
    $this->number->expects($this->once())
                 ->method('get_email')
                 ->willReturn('number@example.com');
    $this->number->expects($this->once())
                 ->method('set_hash');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(
                                  ['numbers/general_access'],
                                  ['numbers/generate_password']
                                )
               ->will($this->onConsecutiveCalls(true, true));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\number', 125)
             ->willReturn($this->number);
    $this->em->expects($this->once())
             ->method('flush');
    $this->app['salt'] = 'salt';
    $message = $this->getMockBuilder('Swift_Message')
                    ->disableOriginalConstructor()
                    ->getMock();
    $message->expects($this->once())
            ->method('setSubject')
            ->with('Пароль в личный кабинет')
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setFrom')
            ->with(['mail@example.com'])
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setTo')
            ->with(['number@example.com'])
            ->will($this->returnValue($message));
    $message->expects($this->once())
            ->method('setBody')
            ->with('body_text');
    $mailer = $this->getMockBuilder('Swift_Mailer')
                   ->disableOriginalConstructor()
                   ->getMock();
    $mailer->expects($this->once())
           ->method('send')
           ->with($this->identicalTo($message));
    $this->app['Swift_Message'] = $message;
    $this->app['mailer'] = $mailer;
    $this->app['email_for_reply'] = 'mail@example.com';
    $this->twig->expects($this->once())
               ->method('render')
               ->willReturn('body_text');
    $model = new model($this->app, $this->twig, $this->em, $this->user, 125);
    $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $model->generate_password());
  }

  public function test_get_dialog_generate_password_1(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(
                                  ['numbers/general_access'],
                                  ['numbers/generate_password']
                                )
               ->will($this->onConsecutiveCalls(true, false));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\number', 125)
             ->willReturn($this->number);
    $model = new model($this->app, $this->twig, $this->em, $this->user, 125);
    $model->get_dialog_generate_password();
  }

  public function test_get_dialog_generate_password_2(){
    $this->user->expects($this->exactly(2))
               ->method('check_access')
               ->withConsecutive(
                                  ['numbers/general_access'],
                                  ['numbers/generate_password']
                                )
               ->will($this->onConsecutiveCalls(true, true));
    $this->em->expects($this->once())
             ->method('find')
             ->with('domain\number', 125)
             ->willReturn($this->number);
    $this->twig->expects($this->once())
               ->method('render')
               ->with('number\get_dialog_generate_password.tpl', ['number' => $this->number])
               ->will($this->returnValue('render_template'));
    $model = new model($this->app, $this->twig, $this->em, $this->user, 125);
    $this->assertEquals('render_template', $model->get_dialog_generate_password());
  }
}