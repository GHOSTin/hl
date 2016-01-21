<?php namespace tests\main\models;

use Silex\Application;
use client\models\registration as model;
use PHPUnit_Framework_TestCase;

class registration_Test extends PHPUnit_Framework_TestCase{

  const email = 'target@example.com';
  const number = '038464888';
  const address = 'Ватутина 52 кв 19';
  const fio = 'Некрасов Евгений Валерьевич';
  const telephone = '65-78-96';
  const cellphone = '+792229444742';

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
    $this->number = $this->getMock('domain\number');
    $this->app = new Application();
    $this->model = new model($this->twig, $this->em, $this->logger);
    $this->context = [
      'number' => self::number,
      'fio' => self::fio,
      'address' => self::address,
      'email' => self::email,
      'tellephone' => self::telephone,
      'cellphone' => self::cellphone
    ];
  }

  public function test_registration_form(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('registration/registration_form.tpl', ['number' => null])
               ->will($this->returnValue('render_template'));
    $this->assertEquals('render_template', $this->model->registration_form($this->app));
  }

  public function test_create_request_1(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->with(self::number)
               ->will($this->returnValue(null));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\number')
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('registration/not_found_number.tpl', ['number' => self::number])
               ->will($this->returnValue('render_template'));
    $this->logger->expects($this->once())
                 ->method('addWarning')
                 ->with('Not found number for registration', $this->context);
    $this->logger->expects($this->once())
                 ->method('addInfo')
                 ->with('Begin registration', $this->context);
    $response = $this->model->create_request(self::number,
                                              self::fio,
                                              self::address,
                                              self::email,
                                              self::telephone,
                                              self::cellphone);
    $this->assertEquals('render_template', $response);
  }

  public function test_create_request_2(){
    $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                       ->disableOriginalConstructor()
                       ->setMethods(['findOneByNumber'])
                       ->getMock();
    $repository->expects($this->once())
               ->method('findOneByNumber')
               ->with(self::number)
               ->will($this->returnValue($this->number));
    $this->em->expects($this->once())
             ->method('getRepository')
             ->with('domain\number')
             ->will($this->returnValue($repository));
    $this->twig->expects($this->once())
               ->method('render')
               ->with('registration/success.tpl')
               ->will($this->returnValue('render_template'));
    $this->logger->expects($this->exactly(2))
                 ->method('addInfo')
                 ->withConsecutive(
                                ['Begin registration', $this->context],
                                ['Create registration request', $this->context]
                               );
    $response = $this->model->create_request(self::number,
                                              self::fio,
                                              self::address,
                                              self::email,
                                              self::telephone,
                                              self::cellphone);
    $this->assertEquals('render_template', $response);
  }
}