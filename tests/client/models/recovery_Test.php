<?php namespace tests\main\models;

use Silex\Application;
use client\models\recovery as model;
use PHPUnit_Framework_TestCase;

class recovery_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->logger = $this->getMockBuilder('Monolog\Logger')
                         ->disableOriginalConstructor()
                         ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->number = $this->getMock('domain\number');
    $this->api_key = $this->getMock('domain\api_key');
    $this->app = new Application();
  }

  public function test_recovery_form(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('recovery/default_page.tpl')
               ->will($this->returnValue('render_template'));
    $model = new model($this->twig, $this->em, $this->logger);
    $this->assertEquals('render_template', $model->recovery_form($this->app));
  }

  public function test_recovery_password_1(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->with('125')
               ->will($this->returnValue(null));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\number')
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('recovery/not_found_number.tpl', ['number' => '125'])
               ->will($this->returnValue('render_template'));
    $this->logger->expects($this->once())
                 ->method('addWarning')
                 ->with('Recovery number not exists', [1,2,3]);
    $model = new model($this->twig, $this->em, $this->logger);
    $this->assertEquals('render_template', $model->recovery(
                                                                  '125',
                                                                  'salt',
                                                                  'message',
                                                                  'mailer',
                                                                  'no_reply',
                                                                  [1,2,3],
                                                                  'site_url'
                                                                ));
  }

  public function test_recovery_password_2(){
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
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\number')
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('recovery/email_not_exists.tpl', ['number' => '125'])
               ->will($this->returnValue('render_template'));
    $this->logger->expects($this->once())
                 ->method('addWarning')
                 ->with('Recovery email not exists', [1,2,3]);
    $model = new model($this->twig, $this->em, $this->logger);
    $this->assertEquals('render_template', $model->recovery(
                                                                  '125',
                                                                  'salt',
                                                                  'message',
                                                                  'mailer',
                                                                  'no_reply',
                                                                  [1,2,3],
                                                                  'site_url'
                                                                ));
  }

  public function test_recovery_password_3(){
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
               ->with('125')
               ->will($this->returnValue($number));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\number')
             ->will($this->returnValue($repository));
    $this->em->expects($this->once())
             ->method('flush');
    $this->twig->expects($this->exactly(2))
               ->method('render')
               ->withConsecutive(
                 ['recovery\generate_password.tpl', $this->anything()],
                 ['recovery/success.tpl', ['number' => null]]
               )
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
            ->with(['no_reply'])
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
    $this->logger->expects($this->once())
                 ->method('addInfo')
                 ->with('Recovery success', [1,2,3]);
    $model = new model($this->twig, $this->em, $this->logger);
    $this->assertEquals('render_template', $model->recovery(
                                                                  '125',
                                                                  'salt',
                                                                  $message,
                                                                  $mailer,
                                                                  'no_reply',
                                                                  [1,2,3],
                                                                  'site_url'
                                                                ));
  }
}