<?php namespace tests\main\models;

use Silex\Application;
use main\models\registrations as model;
use PHPUnit_Framework_TestCase;

class registrations1_Test extends PHPUnit_Framework_TestCase{

  public function setUp(){
    $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->disableOriginalConstructor()
                     ->getMock();
    $this->logger = $this->getMockBuilder('Monolog\Logger')
                         ->disableOriginalConstructor()
                         ->getMock();
    $this->twig = $this->getMockBuilder('Twig_Environment')
                       ->disableOriginalConstructor()
                       ->getMock();
    $this->user = $this->getMock('domain\user');
    $this->app = new Application();
  }

  public function test_construct(){
    $this->setExpectedException('RuntimeException');
    $this->user->expects($this->once())
               ->method('check_access')
               ->with('system/general_access')
               ->willReturn(false);
    new model($this->twig, $this->em, $this->user, $this->logger);
  }
}