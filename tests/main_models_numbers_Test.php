<?php

use Silex\Application;
use main\models\numbers as model;

class main_model_numbers_Test extends PHPUnit_Framework_TestCase{

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

  public function test_construct(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user);
  }

  public function test_default_page(){
    $this->twig->expects($this->once())
               ->method('render')
               ->with('number\default_page.tpl',
                     [
                      'user' => $this->user,
                      'streets' => 'street_array'
                     ])
               ->will($this->returnValue('render_template'));
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('numbers/general_access')
               ->willReturn(true);
    $model = $this->getMockBuilder('main\models\numbers')
                  ->setConstructorArgs([$this->twig, $this->em, $this->user])
                  ->setMethods(['get_streets'])
                  ->getMock();
    $model->expects($this->once())
          ->method('get_streets')
          ->willReturn('street_array');
    $this->assertEquals('render_template', $model->default_page());
  }
}